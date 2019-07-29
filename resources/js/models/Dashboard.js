class Dashboard{
	
	static getUserstoryProgress(then) {
		return axios.get('/dashboard/userstoryprogress')
			.then(({data}) => then(data))
	}

	static getUserstoryPending(then) {
		return axios.get('/dashboard/userstorypending')
			.then(({data}) => then(data))
	}

	static getProjectsDeadlinePassed(then) {
		return axios.get('/dashboard/projectsdeadline')
			.then(({data}) => then(data))	
	}

	static getProjects(then) {
		return axios.get('/dashboard/projects')
			.then(({data}) => then(data))
	}

	static getProjectTasks(then) {
		return axios.get('/dashboard/projecttask')
			.then(({data}) => then(data))	
	}

	static getCommentFeed(then) {
		return axios.get('/dashboard/commentfeed')
			.then(({data}) => then(data))	
	}

	static getTaskDeadlinePassed(then) {
		return axios.get('/dashboard/taskdeadlinepassed')
			.then(({data}) => then(data))		
	}

	static getAllUpcomingTasks(then) {
		return axios.get('/dashboard/upcomingtasks')
			.then(({data}) => then(data))		
	}
	
}

export default Dashboard;