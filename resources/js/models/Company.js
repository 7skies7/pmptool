class Company{
	
	static all(then) {
		return axios.get('/company')
			.then(({data}) => then(data));
	}
}

export default Company;