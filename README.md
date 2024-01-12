# sensu-dashboard
Uses Sensu's Redis instance to provide a simple interface for sensors.
Currently gives a datetime of when sensors were last run, and what their current status is.

## Installation
```
composer install
```

```
cd js/
yarn install
```

```
cp config.json.example config.json
```

## Build
```
phing build
```

### Build JS
Runs under phing build
```
cd js/
yarn run build
```
