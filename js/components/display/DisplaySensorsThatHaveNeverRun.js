import React from 'react'

const DisplaySensorsThatHaveNeverRun = ({data}) => {
    //needs changing to be async
    if (!data) {
        return <div/>
    }

    let sensorString = data.join(', ')

    return (
        <div className="newSensorsWarning">
            <strong>Warning - the following sensors have never run: </strong>
                {sensorString}
        </div>
    )
}
export default DisplaySensorsThatHaveNeverRun;
