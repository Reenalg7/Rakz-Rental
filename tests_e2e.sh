#!/bin/bash
# Rakz Rental E2E curl-based tests
BASE="https://vehicle-fleet-12.preview.emergentagent.com"
PASS=0; FAIL=0
check() {
  local name="$1" expected="$2" actual="$3"
  if [[ "$actual" == *"$expected"* ]]; then
    echo "PASS: $name"; PASS=$((PASS+1))
  else
    echo "FAIL: $name (expected '$expected' in '$actual')"; FAIL=$((FAIL+1))
  fi
}

# ===== Public pages =====
for p in / /vehicles /login /register "/vehicles/LR3180"; do
  code=$(curl -sk -o /tmp/_p.html -w "%{http_code}" $BASE$p)
  err=$(grep -c "Session store not set" /tmp/_p.html)
  check "public $p 200" "200" "$code"
  check "public $p no session err" "0" "$err"
done

# ===== Customer login =====
C=/tmp/cust.txt; rm -f $C
curl -sk -c $C -b $C $BASE/login -o /tmp/l.html
T=$(grep -oP 'name="_token" value="\K[^"]+' /tmp/l.html | head -1)
U=$(curl -sk -c $C -b $C -L -X POST -d "_token=$T&email=customer@rakzrental.com&password=customer123" $BASE/login -o /dev/null -w "%{url_effective}")
check "customer login redirect to /vehicles" "/vehicles" "$U"

# Vehicle detail
curl -sk -b $C $BASE/vehicles/LR3180 -o /tmp/vd.html
check "vehicle detail has reg no" "LR3180" "$(cat /tmp/vd.html)"
check "vehicle detail has book button" "/my/book/LR3180" "$(cat /tmp/vd.html)"

# Search by reg no
curl -sk -b $C "$BASE/vehicles?q=LR3180" -o /tmp/sr.html
check "search LR3180 returns match" "LR3180" "$(cat /tmp/sr.html)"

# Booking create page
curl -sk -b $C $BASE/my/book/LR3180 -o /tmp/bc.html -w "bc:%{http_code}\n"
check "booking create form has pickup" "booking-pickup" "$(cat /tmp/bc.html)"
check "booking create form has return" "booking-return" "$(cat /tmp/bc.html)"
T2=$(grep -oP 'name="_token" value="\K[^"]+' /tmp/bc.html | head -1)

# Check-availability AJAX
TM=$(date -u -d "+2 days" +%Y-%m-%d); TA=$(date -u -d "+4 days" +%Y-%m-%d)
curl -sk -b $C -c $C -H "X-CSRF-TOKEN: $T2" -H "Content-Type: application/json" -H "Accept: application/json" \
  -X POST -d "{\"pickup_date\":\"$TM\",\"return_date\":\"$TA\"}" \
  $BASE/my/book/LR3180/check-availability -o /tmp/ca.html -w "ca:%{http_code}\n"
cat /tmp/ca.html; echo
check "availability=true" "true" "$(cat /tmp/ca.html)"

# Create booking
RL=$(curl -sk -b $C -c $C -X POST \
  -d "_token=$T2&pickup_date=$TM&return_date=$TA&notes=TEST_e2e" \
  $BASE/my/book/LR3180 -o /dev/null -w "%{redirect_url}")
echo "booking redirect: $RL"
curl -sk -b $C "$RL" -o /tmp/bk.html -w "bk_show:%{http_code}\n"
check "booking show has Pending" "Pending" "$(cat /tmp/bk.html)"
check "booking show has Reference" "Reference" "$(cat /tmp/bk.html)"

# Double booking - availability should be false
curl -sk -b $C -c $C -H "X-CSRF-TOKEN: $T2" -H "Content-Type: application/json" -H "Accept: application/json" \
  -X POST -d "{\"pickup_date\":\"$TM\",\"return_date\":\"$TA\"}" \
  $BASE/my/book/LR3180/check-availability -o /tmp/ca2.html
echo "DBL BOOK CHECK: $(cat /tmp/ca2.html)"
check "double booking blocked" "false" "$(cat /tmp/ca2.html)"

# My bookings listing
curl -sk -b $C $BASE/my/bookings -o /tmp/mb.html
check "my bookings has LR3180" "LR3180" "$(cat /tmp/mb.html)"

# Favorites toggle (need fresh csrf from /vehicles)
curl -sk -b $C $BASE/vehicles -o /tmp/vs.html
TF=$(grep -oP 'content="\K[^"]+(?=" name="csrf-token")|name="csrf-token" content="\K[^"]+' /tmp/vs.html | head -1)
curl -sk -b $C -c $C -L -X POST -d "_token=$TF" $BASE/my/favorites/LR3181/toggle -o /tmp/fv.html -w "fav:%{http_code}\n"
curl -sk -b $C $BASE/my/favorites -o /tmp/mf.html
check "favorites has LR3181" "LR3181" "$(cat /tmp/mf.html)"

# ===== Admin login =====
A=/tmp/adm.txt; rm -f $A
curl -sk -c $A -b $A $BASE/login -o /tmp/al.html
T=$(grep -oP 'name="_token" value="\K[^"]+' /tmp/al.html | head -1)
U=$(curl -sk -c $A -b $A -L -X POST -d "_token=$T&email=admin@rakzrental.com&password=admin123" $BASE/login -o /dev/null -w "%{url_effective}")
check "admin login redirects to /admin" "/admin" "$U"

# Dashboard
curl -sk -b $A $BASE/admin -o /tmp/ad.html -w "admin:%{http_code}\n"

# Reports page - the big bug2 verification
curl -sk -b $A $BASE/admin/reports -o /tmp/rep.html -w "reports:%{http_code}\n"
for section in "Revenue" "utilization" "Top customers" "canvas"; do
  check "reports has $section" "$section" "$(cat /tmp/rep.html)"
done
check "no DATE_FORMAT err" "0" "$(grep -c DATE_FORMAT /tmp/rep.html)"
check "no ambiguous err" "0" "$(grep -ci "ambiguous column" /tmp/rep.html)"

# CSV exports
for p in bookings revenue utilization customers audit-logs; do
  ct=$(curl -sk -b $A -o /tmp/cx.csv -w "%{content_type}" $BASE/admin/export/$p.csv)
  check "csv $p content-type" "text/csv" "$ct"
done

# Admin vehicles edit page - reg_no readonly
curl -sk -b $A $BASE/admin/vehicles/LR3180/edit -o /tmp/ve.html
check "edit regno readonly" "YES" "$(grep -q readonly /tmp/ve.html && echo YES || echo NO)"

# Admin bookings
curl -sk -b $A $BASE/admin/bookings -o /tmp/ab.html
check "admin bookings 200" "LR3180" "$(cat /tmp/ab.html)"
BID=$(grep -oP '/admin/bookings/\K\d+' /tmp/ab.html | head -1)
echo "Admin booking id: $BID"
if [ -n "$BID" ]; then
  curl -sk -b $A $BASE/admin/bookings/$BID -o /tmp/abd.html
  check "admin booking detail has replace" "admin-replace-vehicle" "$(cat /tmp/abd.html)"
  check "admin booking detail status select" "admin-booking-status-select" "$(cat /tmp/abd.html)"
  TBD=$(grep -oP 'name="_token" value="\K[^"]+' /tmp/abd.html | head -1)
  # Update status to confirmed
  curl -sk -b $A -X POST -d "_token=$TBD&status=confirmed" $BASE/admin/bookings/$BID/status -o /dev/null -w "status_upd:%{http_code}\n"
  curl -sk -b $A $BASE/admin/bookings/$BID -o /tmp/abd2.html
  check "status updated to confirmed" "onfirmed" "$(cat /tmp/abd2.html)"
fi

# Audit logs
curl -sk -b $A $BASE/admin/audit-logs -o /tmp/al2.html
check "audit logs has login" "login" "$(cat /tmp/al2.html)"
check "audit logs has booking.create" "booking.create" "$(cat /tmp/al2.html)"

# Replacements
curl -sk -b $A $BASE/admin/replacements -o /tmp/rp.html -w "repl:%{http_code}\n"

# PDF invoice as customer
curl -sk -b $C $BASE/my/bookings -o /tmp/mb2.html
MBID=$(grep -oP '/my/bookings/\K\d+' /tmp/mb2.html | head -1)
if [ -n "$MBID" ]; then
  INV_CT=$(curl -sk -b $C -o /tmp/inv.pdf -w "%{content_type}" $BASE/my/bookings/$MBID/invoice)
  check "invoice PDF content-type" "pdf" "$INV_CT"
fi

# Vehicle datasheet PDF
DS_CT=$(curl -sk -b $A -o /tmp/ds.pdf -w "%{content_type}" $BASE/admin/vehicles/LR3180/datasheet)
check "datasheet PDF content-type" "pdf" "$DS_CT"

echo ""
echo "==========================================="
echo "PASSED: $PASS  FAILED: $FAIL"
echo "==========================================="
exit $FAIL
