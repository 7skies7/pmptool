class User{
	
	static all(then) {
		return axios.get('/user')
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static addaccess(then) {
		return axios.get('/access/Users/Create')
			.then(({data}) => then(data));
	}

	static editaccess(then) {
		return axios.get('/access/Users/Update')
			.then(({data}) => then(data));
	}

	static deleteaccess(then) {
		return axios.get('/access/Users/Delete')
			.then(({data}) => then(data));
	}

	static fetchRole(then) {
		return axios.get('/user/role')
			.then(({data}) => then(data));
	}

	static getTimecard(then) {
		return axios.get('/user/timecard')
			.then(({data}) => then(data));
	}

	static getTodayComment(then) {
		return axios.get('/user/today/comment')
				.then(({data}) => then(data));

	}

	static fetchUserDetails(then) {
		return axios.get('/user/details')
				.then(({data}) => then(data));
	}

	static fetchTimesheet(then) {
		return axios.get('/user/timesheet')
				.then(({data}) => then(data));
	}
}

export default User;