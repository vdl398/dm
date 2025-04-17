import {Request} from './request.js'


class WsClass {
	
	init(connector, port) {
        return new Promise((resolve, reject)=> {
            let ws = new WebSocket('wss://'+Request.APP_DOMAIN+':'+ port +'?u=' + connector);
            ws.onmessage = (message)=> {
	            try {
	                let data = (message.data)? JSON.parse(message.data) : {};
					document.dispatchEvent(new CustomEvent('wsRecv', {detail: data}));	
	            }catch(e) {console.log(e);}
            };

            ws.onopen = ()=> {
                document.addEventListener('wsSend', function (e) {
	                ws.send(JSON.stringify(e.detail));
                }, false);	
				resolve(true);
            };
			
			ws.onerror = (error)=> {
				resolve(false);
            };
        });
	}
	
	send(data={}) {
	    document.dispatchEvent(new CustomEvent('wsSend', {detail: data}));
    }
	
}
const Ws = new WsClass;
export {Ws};