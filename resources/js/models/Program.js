class Program{
	
	static all(then) {
		return axios.get('/program')
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static addaccess(then) {
		return axios.get('/access/Program/Create')
			.then(({data}) => then(data));
	}

	static editaccess(then) {
		return axios.get('/access/Program/Update')
			.then(({data}) => then(data));
	}

	static deleteaccess(then) {
		return axios.get('/access/Program/Delete')
			.then(({data}) => then(data));
	}

	static getCompanies(then) {
		return axios.get('/company/fetchAllCompanies')
			.then(({data}) => then(data));	
	}

}

export default Program;