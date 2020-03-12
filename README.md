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



## Middlewares

**Readers**

- *CsvEnvtReaderMiddleware*: Read 4-col csv files
- *GzipUnpackerMiddleware*: Unpack gzipped files


**Transformers**

- *UnitMapMiddleware*: Map legacy units to standard

**Validators**

- *VerifyUnitsMiddleware*: Skip records that contain units not in the standard

**Writers**

- *OticWriterMiddleware*: Default Writer on `/v1/convert` routes

- *PrintWriterMiddleware*: Default Writer on `/v1/csv` routes

- *NullWriterMiddleware*: A Endpoint for development

- *MockWriterMiddleware*: A Endpoint for development (stores the result)



