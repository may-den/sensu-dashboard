import React, {useState, useEffect} from 'react';
import axios from 'axios';
import DisplaySensorStatus from "./display/DisplaySensorStatus";

export const App = () => {
    const [data, setData] = useState("");

    useEffect(() => {
        axios.get('http://sensu-dashboard.test/checkResult').then(res => {
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
