import React from 'react'
import renderer from 'react-test-renderer'
import DisplaySensorStatus from "../../../components/display/DisplaySensorStatus";

const {describe, it, expect} = global

const data = [
    {
        "client": "sensu-client-ivnode7",
        "check": {
            "command": "php /var/www/html/sensor-manager/SensorManager.php run Iaptus ObjectStorage\\\\Checks\\\\MissingFileCheck",
            "subscribers": [
                "iaptus"
            ],
            "interval": 900,
            "handlers": [
                "mailer",
                "slack"
            ],
            "name": "Mayden.Sensors.Iaptus.ObjectStorage.Checks.MissingFileCheck",
            "issued": 1563781853,
            "executed": 1563781880,
            "duration": 3.088,
            "output": "",
            "status": 0,
            "type": "standard"
        }
    },
    {
        "client": "sensu-client-ivnode7",
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
            "duration": 5.757,
            "output": "sensu.iaptus.bournemouth.iaptusvnode7.availability_count.160_ealing 1 1563781818\n...",
            "status": 0
        }
    },
]

describe('Test the display of Sensor Status', () => {
    it('renders correctly', () => {
        const tree = renderer
            .create(<DisplaySensorStatus data={data} />)
            .toJSON()
        expect(tree).toMatchSnapshot()
    })
})
