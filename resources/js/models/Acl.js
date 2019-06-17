class Acl{
	
	static roles(then) {
		return axios.get('/acl/roles')
			.then(({data}) => then(data));
	}

	static modules(then) {
		return axios.get('/acl/modules')
			.then(({data}) => then(data));
	}

	static actions(then) {
		return axios.get('/acl/actions')
			.then(({data}) => then(data));
	}

	static getRoleAccessList(then, roleid) {
		return axios.get('/acl/getaccess/'+roleid)
			.then(({data}) => then(data));
	}

	static getUserDetails(then) {
		return axios.get('/acl/userdetails')
			.then(({data}) => then(data));
	}
}

export default Acl;