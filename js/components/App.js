import React, {useState, useEffect} from 'react';
import axios from 'axios';
import DisplaySensorStatus from "./display/DisplaySensorStatus";

export const sortOnName = (a, b) => {
    if (a.check.name === b.check.name) {
        return 0;
    }
    else {
        return (a.check.name< b.check.name) ? -1 : 1;
    }
}

export const sortOnStatus = (a, b) => {
    if (a.check.status === b.check.status) {
        return 0;
    }
    else {
        return (a.check.status > b.check.status) ? -1 : 1;
    }
}

export const App = () => {
    const [data, setData] = useState("");

    useEffect(() => {
        axios.get('http://sensu-dashboard.test/checkResult').then(res => {
            res.data.sort(sortOnName);
            res.data.sort(sortOnStatus);

            setData(res.data);
        }).catch(e=>{
            console.log(e);
        });
    }, []);

    return (
        <div>
            <DisplaySensorStatus data={data} />
        </div>
    )
}
