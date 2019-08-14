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
                <div className="statusCodeHeading">(Code)</div>
                <div className="timeHeading">Time</div>
                <div className="messageHeading">Message</div>
            </div>
            {
                data.map(datum => {
                    var dateString = new Date(datum.check.executed*1000).toLocaleDateString();
                    var date = new Date(datum.check.executed * 1000);
                    let time = date.getHours() + ':' + ("0" + date.getMinutes()).substr(-2) + ':' + ("0" + date.getSeconds()).substr(-2);

                    let statusColour = datum.check.status === 0 ? "statusItemGreen" : "statusItemRed";
                    let printMessage = statusColour === "statusItemGreen" ?  "" : datum.check.output;

                    let name = datum.check.name.replace("Mayden.Sensors.", "");

                    return (
                        <div className="grid-container">
                            <div className="nameItem">{name}</div>
                            <div className ={statusColour + "Dot"}/>
                            <div className="statusCodeItem">({datum.check.status})</div>
                            <div className="timeItem">{time + " " + dateString}</div>
                            <div className="messageItem">{printMessage}</div>
                        </div>
                    )
                })
            }
        </div>
    )
}
export default DisplaySensorStatus;
