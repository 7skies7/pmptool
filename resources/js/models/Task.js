class Task{
	
	static all(then, projectid) {
		return axios.get('/task/'+ projectid)
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static allUserTask(then, projectid) {
		return axios.get('/user/tasks')
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static allSubtask(then, taskid) {
		return axios.get('/task/subtask/'+ taskid)
			.then(({data}) => then(data))
			.catch(error => {
				if(error.response.status == 403)
				{
					window.location.href = "/403";
				}
			});
	}

	static getRemainingPoints(then, point, taskid) {
		return axios.get('/task/subtask/'+point+'/'+taskid)
			.then(({data}) => then(data));
	}

	static getTaskRemainingPoints(then, userpoint_id, task_type) {
		return axios.get('/task/availablepoints/'+userpoint_id+'/'+task_type)
			.then(({data}) => then(data));
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

	static allComments(then, taskid) {
		return axios.get('/task/allcomments/'+taskid)
			.then(({data}) => then(data));
	}
	static allUserStory(then, projectid) {
		return axios.get('/task/userstory/'+projectid)
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

	static allUsers(then) {
		return axios.get('/company/getResources/')
			.then(({data}) => then(data));
	}

	static allStatus(then) {
		return axios.get('/project/getStatus/')
			.then(({data}) => then(data));
	}

	static allPriority(then) {
		return axios.get('/project/getPriority/')
			.then(({data}) => then(data));
	}

	static allType(then) {
		return axios.get('/project/getTaskType')
			.then(({data}) => then(data));
	}

	static getTask(then, taskid) {
		return axios.get('/task/edit/'+taskid)
			.then(({data}) => then(data));
	}

	static getAvailableHours(then, taskpoint, taskid) {
		return axios.get('/task/availablehours/'+taskpoint+'/'+taskid)
			.then(({data}) => then(data));
	}
	
}

export default Task;