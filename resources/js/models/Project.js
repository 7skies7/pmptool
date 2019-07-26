class Project{
	
	static all(then) {
		return axios.get('/project')
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static addaccess(then) {
		return axios.get('/access/Project/Create')
			.then(({data}) => then(data));
	}

	static editaccess(then) {
		return axios.get('/access/Project/Update')
			.then(({data}) => then(data));
	}

	static deleteaccess(then) {
		return axios.get('/access/Project/Delete')
			.then(({data}) => then(data));
	}

	static getPrograms(then) {
		return axios.get('/project/getPrograms')
			.then(({data}) => then(data));
	}

	static getResources(then) {
		return axios.get('/company/getResources')
			.then(({data}) => then(data));
	}

	static getStatus(then) {
		return axios.get('/project/getStatus')
			.then(({data}) => then(data));
	}
}

export default Project;