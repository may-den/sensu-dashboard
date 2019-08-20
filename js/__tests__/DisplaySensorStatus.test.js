import React from 'react';
import renderer from "react-test-renderer";
import DisplaySensorStatus from "../components/display/DisplaySensorStatus";

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
            "client": "sensu-client-cron01.iaptus.ukc",
            "check": {
                "command": "php /var/www/html/sensor-manager/SensorManager.php run Iaptus Sms\\\\Checks\\\\SendingOnTime",
                "subscribers": [
                    "iaptus"
                ],
                "interval": 1800,
                "handlers": [
                    "mailer",
                    "slack"
                ],
                "name": "Mayden.Sensors.Iaptus.Sms.Checks.SendingOnTime",
                "issued": 1562772496,
                "executed": 1562772513,
                "duration": 13.33,
                "output": "{\"errors\":[{\"value\":\"1\"},{\"value\":\"16\"}]}\n",
                "status": 2,
                "type": "standard"
            }
        },
    ]

describe('Display Component', () => {
    it('matches the snapshot', () => {
        const tree = renderer
            .create(<DisplaySensorStatus data={data}/>)
            .toJSON()
        expect(tree).toMatchSnapshot()
    })
})