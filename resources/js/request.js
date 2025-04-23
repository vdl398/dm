import axios from 'axios'

class RequestAjax {

    APP_DOMAIN = 'dmtest.ru';

	post(action, data=null, param={}) {
        return new Promise((resolve, reject)=> {
			if (!data) resolve(false);
			let params = {};
			if (param && param['headers']) params.headers = param.headers;
            axios.post('https://'+this.APP_DOMAIN+'/'+action, (param && param['formData'])? data : JSON.stringify(data), params) 
            .then((response) => {
                resolve(response.data?.data);
            })
            .catch((error) => {
				resolve({
					status: 'error',
					errors: [],
					response: error.response
				});
            });
        });
    }
	
	
	get(action, data={}) {
        return new Promise((resolve, reject)=> {
			let query = new URLSearchParams(data);
            axios.get('https://'+this.APP_DOMAIN+'/'+action+'?'+query.toString())
            .then((response) => {
                resolve(response.data?.data);
            })
            .catch((error) => {
				resolve({
					status: 'error',
					errors: [],
					response: error.response
				});
            });
        });
    }
	
	
}
const Request = new RequestAjax;
export {Request};