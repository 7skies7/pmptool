class Role{
	
	static all(then) {
		return axios.get('/roles')
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}
}

export default Role;