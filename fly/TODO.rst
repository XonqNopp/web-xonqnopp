Fly
###


Nav
***

* GcValidatedToff GcValidatedZeroFuel
* MLDGW also highlight red if overweight on take-off.
  Compute LDG weight, highlight something and add note if too much (in row of min GC, column weight)
* Max fuel: put minus overflow and highlight if not possible (based on usable).
* Display max range with full fuel minus 1h
* 2 fuel locations in order of filling
* Review number of latex lines, incl. when doing roundtrip
* Add note about GSx5 for descending (TOD)
* Improve display of formula for TH with wind (both html+latex)
* Move fuel/h in table (first cell?)
  \multicolumn{4}{r}{\bf Fuel per hour:} & \multicolumn{2}{r}{value} & \multicolumn{1}{l}{unit}\\\hhline{~------}
* Change fuel headers to NoWind, Wind
* Increment nav tex version (hardcoded timestamp)
* Change nav.tex header to all multicolumn{2} or single line with same settings as table below
* no wind(MH) if visual
* store GC validated 2x
* display dry timestamp on plane list
* logbook: add summary when 3-month expires
* Improve bind_ SQL stuff on multiple lines, with $params and $values
* split long SQL request on many lines
* NavDetails: check for rear+luggage, if mass>0 and arm==0 raise error


Fuel req
********

GC Data only holds MassMomentObjects (mass, arm, moment with units).
It includes stations for unusable and quantity fuel for all tanks.

FuelReq holds FuelTank objects (unusable, quantity... but no GC data like arm).
When FUelReq is ready, we feed it to GC data to copy the fuel quantity.
ALl other required data should have been previously set.

We decouple fuel computation from GC computation.
First we compute everything related to fuel in FuelReq.
Inputs: flight time, fuel type, fuel unit.

Then we feed the FUelReq object to gcData but we only copy the quantity as mass.
GC Data do not need to know about fuel type/unit.


GC data
*******

All mass inputs are [kg] except DryEMpty.
To compute GC data, we must convert all to the same as DryEmpty.
When do we need [kg]? Never. So if we need to convert, we store and that's it.
How to know if we are storing kg or lbs?
It is better to keep internal storage to kg so we always deal with kg.
Should we only convert to lbs when we compute GC data?


Logbook
*******

* check fields size on mobile
* reset fields for notes to not add unwanted time/landings


Misc
****

* Add github link somewhere (fork me?) testament, logbook, nav...
* Check all links



Other
#####

* Check hypotheque links

