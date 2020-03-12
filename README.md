# otic-tools


## Benchgen

Create reproducible csv output for testing timeseries

**Create 30min data (sampled 0.1 hz) 18000 datasets with 1000 sensor names**

```
benchgen -n 18000 -s 1000  -o /tmp/test.csv
oticpack -i /tmp/test.csv -o /tmp/test.otic
dumpotic -i /tmp/test.otic --skipout
```

**Using direct chaining**

```
benchgen | oticpack | dumpotic
```


## Api

Dump all sensors from sting otic input

```php
$data = OticDump::Dump("...otic-binary-data...", $stats);

echo "Sensors read: " . $stats["sensors_read"]
```



