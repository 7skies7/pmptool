class Userstory{
	
	static all(then, scopeid) {
		return axios.get('/userstory/'+ scopeid)
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static addaccess(then) {
		return axios.get('/access/Userstory/Create')
			.then(({data}) => then(data));
	}

	static editaccess(then) {
		return axios.get('/access/Userstory/Update')
			.then(({data}) => then(data));
	}

	static deleteaccess(then) {
		return axios.get('/access/Userstory/Delete')
			.then(({data}) => then(data));
	}

	static allComments(then, userstoryid) {
		return axios.get('/userstory/comments/'+userstoryid)
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static fetchUserstory(then, userstoryid) {
		return axios.get('/userstory/edit/'+userstoryid)
			.then(({data}) => then(data));
	}

	static fetchDocuments(then, userstoryid) {
		return axios.get('/userstory/documents/'+userstoryid)
			.then(({data}) => then(data));
	}

	static fetchApprovedDocument(then, userstoryid) {
		return axios.get('/userstory/document/'+userstoryid)
			.then(({data}) => then(data));
	}
	
}

export default Userstory;