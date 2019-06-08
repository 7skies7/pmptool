class Acl{
	
	static roles(then) {
		return axios.get('/roles')
			.then(({data}) => then(data));
	}
}

export default Acl;