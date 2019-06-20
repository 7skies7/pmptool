class Company{
	
	static all(then) {
		return axios.get('/company')
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static addaccess(then) {
		return axios.get('/access/Organization/Create')
			.then(({data}) => then(data));
	}

	static editaccess(then) {
		return axios.get('/access/Organization/Update')
			.then(({data}) => then(data));
	}

	static deleteaccess(then) {
		return axios.get('/access/Organization/Delete')
			.then(({data}) => then(data));
	}
}

export default Company;