import {Request} from './request.js'

class AuthClass {
	
	constructor() {
        this.user = {id: 0};
    }

	init() {
		return new Promise((resolve, reject)=> {
            Request.get('auth', {}).then((rs)=> {
	            if (rs && rs.user?.id) {
			        this.user = Object.assign({}, rs.user);
	            }
		        resolve(true);
			});
		});
    }
	
	getUser() {
		return Object.assign({}, this.user);
    }
	
	isAuth() {
		return (this.user.id > 0);
    }
	
}
const Auth = new AuthClass();

export {Auth};