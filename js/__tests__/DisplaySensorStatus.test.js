import React from 'react';
import renderer from "react-test-renderer";
import DisplaySensorStatus from "../components/display/DisplaySensorStatus";

const noData = []

const data =
    [{
        "client": "sensu-client-ivnode14",
        "check": {
            "type": "metric",
            "command": "php /var/www/html/sensor-manager/SensorManager.php run Iaptus Availabilities\\\\Metrics\\\\AvailabilityCreatedCount",
            "subscribers": [
                "iaptus"
            ],
            "interval": 450,
            "handlers": [
                "relay"
            ],
            "name": "Mayden.Sensors.Iaptus.Availabilities.Metrics.AvailabilityCreatedCount",
            "issued": 1563781786,
            "executed": 1563781813,
            "duration": 17.94,
            "output": "sensu.iaptus.bracknell.iaptusvnode14.availability_count.12_gloucester 1 1563781830\n...",
            "status": 0
        }
    },
        {
            "client": "sensu-client-ivnode7",
            "check": {
                "thresholds": {
                    "warning": 120,
                    "critical": 180
                },
                "name": "keepalive",
                "issued": 1565620675,
                "executed": 1565620675,
                "output": "No keepalive sent from client for 1838727 seconds (>=180)",
                "status": 2,
                "type": "standard"
            }
        }
    ]

const noNameData = [
    {
        "client": "sensu-client-ivnode14",
        "check": {
            "type": "metric",
            "command": "php /var/www/html/sensor-manager/SensorManager.php run Iaptus Availabilities\\\\Metrics\\\\AvailabilityCreatedCount",
            "subscribers": [
                "iaptus"
            ],
            "interval": 450,
            "handlers": [
                "relay"
            ],
            "issued": 1563781786,
            "executed": 1563781813,
            "duration": 17.94,
            "output": "sensu.iaptus.bracknell.iaptusvnode14.availability_count.12_gloucester 1 1563781830\n...",
            "status": 0
        }
    }
]

const noStatusData = [
    {
        "client": "sensu-client-ivnode14",
        "check": {
            "type": "metric",
            "command": "php /var/www/html/sensor-manager/SensorManager.php run Iaptus Availabilities\\\\Metrics\\\\AvailabilityCreatedCount",
            "subscribers": [
                "iaptus"
            ],
            "interval": 450,
            "handlers": [
                "relay"
            ],
            "name": "Mayden.Sensors.Iaptus.Availabilities.Metrics.AvailabilityCreatedCount",
            "issued": 1563781786,
            "executed": 1563781813,
            "duration": 17.94,
            "output": "sensu.iaptus.bracknell.iaptusvnode14.availability_count.12_gloucester 1 1563781830\n...",
        }
    }
]

const noExecutedData = [
    {
        "client": "sensu-client-ivnode14",
        "check": {
            "type": "metric",
            "command": "php /var/www/html/sensor-manager/SensorManager.php run Iaptus Availabilities\\\\Metrics\\\\AvailabilityCreatedCount",
            "subscribers": [
                "iaptus"
            ],
            "interval": 450,
            "handlers": [
                "relay"
            ],
            "name": "Mayden.Sensors.Iaptus.Availabilities.Metrics.AvailabilityCreatedCount",
            "issued": 1563781786,
            "duration": 17.94,
            "output": "sensu.iaptus.bracknell.iaptusvnode14.availability_count.12_gloucester 1 1563781830\n...",
            "status": 0
        }
    }
]

describe('Display Component', () => {
    it('matches the snapshot with all data', () => {
        const tree = renderer
            .create(<DisplaySensorStatus data={data}/>)
            .toJSON()
        expect(tree).toMatchSnapshot()
    })

    it('does not have data', () => {
        const tree = renderer
            .create(<DisplaySensorStatus data = {noData}/>)
            .toJSON()
        expect(tree).toMatchSnapshot()
    })

    it('does not have name data', () => {
        const tree = renderer
            .create(<DisplaySensorStatus data ={noNameData}/>)
            .toJSON()
        expect(tree).toMatchSnapshot()
    })

    it('does not have status data', () => {
        const tree = renderer
            .create(<DisplaySensorStatus data = {noStatusData}/>)
            .toJSON()
        expect(tree).toMatchSnapshot()
    })

    it('does not have executed data', () => {
        const tree = renderer
            .create(<DisplaySensorStatus data = {noExecutedData}/>)
            .toJSON()
        expect(tree).toMatchSnapshot()
    })

})