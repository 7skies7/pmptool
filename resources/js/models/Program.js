class Program{
	
	static all(then) {
		return axios.get('/program')
			.then(({data}) => then(data));
	}
}

export default Program;