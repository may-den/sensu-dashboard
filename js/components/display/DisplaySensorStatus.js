import React from 'react'

const DisplaySensorStatus = ({data}) => {

    //needs changing to be async
    if (!data) {
        return <div/>
    }

    return (
        <div>
            <div className="grid-container">
                <div className="serviceHeading">Service</div>
                <div className="nameHeading">Name</div>
                <div className="statusHeading">Status</div>
                <div className="statusCodeHeading">(Code)</div>
                <div className="timeHeading">Time</div>
                <div className="messageHeading">Message</div>
            </div>

            {
                data.map((datum, index) => {
                    let date = new Date(datum.check.executed * 1000);
                    let dateString = date.toLocaleDateString();
                    let time = date.getHours() + ':' + ("0" + date.getMinutes()).substr(-2) + ':' + ("0" + date.getSeconds()).substr(-2);

                    let statusColour = datum.check.status === 0 ? "statusItemGreen" : "statusItemRed";
                    let printMessage = statusColour === "statusItemRed" ?  datum.check.output : "";

                    let name = datum.check.name.replace("Mayden.Sensors.", "");

                    // this is to split up the long sensor name
                    let serviceName = (name.split(".", 2)).join(".");
                    name = name.replace(serviceName + ".", "");

                    return (
                        <div className="grid-container" key={index}>
                            <div className="serviceItem">{serviceName}</div>
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
