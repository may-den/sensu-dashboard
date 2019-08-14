import React from 'react'

const DisplaySensorStatus = ({data}) => {
    if (!data) {
        return <div>No data yet pls give</div>
    }

    return (
        <div>
            <div className="grid-container">
                <div className="nameHeading">Name</div>
                <div className="statusHeading">Status</div>
                <div className="timeHeading">Time</div>
            </div>
            {
                data.map(datum => {
                    var date = new Date(datum.check.executed * 1000);
                    let time = date.getHours() + ':' + ("0" + date.getMinutes()).substr(-2) + ':' + ("0" + date.getSeconds()).substr(-2);

                    let statusColour = datum.check.status === 0 ? "statusItemGreen" : "statusItemRed";

                    return (
                        <div className="grid-container">
                            <div className={statusColour + "NameItem"}>{datum.check.name}</div>
                            <div className={statusColour}>{datum.check.status}</div>
                            <div className="timeItem">{time}</div>
                        </div>
                    )
                })
            }
        </div>
    )
}
export default DisplaySensorStatus;
