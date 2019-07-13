class Scope{
	
	static all(then) {
		return axios.get('/scope')
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static addaccess(then) {
		return axios.get('/access/Scope/Create')
			.then(({data}) => then(data));
	}

	static editaccess(then) {
		return axios.get('/access/Scope/Update')
			.then(({data}) => then(data));
	}

	static deleteaccess(then) {
		return axios.get('/access/Scope/Delete')
			.then(({data}) => then(data));
	}

	static allComments(then, crdid) {
		return axios.get('/scope/comments/'+crdid)
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static fetchScope(then, crdid) {
		return axios.get('/userstory/edit/'+crdid)
			.then(({data}) => then(data));
	}

	static fetchDocuments(then, crdid) {
		return axios.get('/scope/documents/'+crdid)
			.then(({data}) => then(data));
	}

	static fetchApprovedDocument(then, crdid) {
		return axios.get('/scope/document/'+crdid)
			.then(({data}) => then(data));
	}

	static allProjectScope(then, projectid) {
		return axios.get('/scope/project/'+projectid)
			.then(({data}) => then(data));
	}

	static allScopeUserstories(then, scopeid) {
		return axios.get('/scope/userstory/'+scopeid)
			.then(({data}) => then(data));
	}
	
}

export default Scope;