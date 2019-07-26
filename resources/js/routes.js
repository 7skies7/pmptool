import VueRouter from 'vue-router'

var routes = [
	{
		path: '/',
		component: require('./views/Dashboard/index').default
	},
	{
		path: '/dashboard',
		component: require('./views/Dashboard/index').default
	},
	{
		path: '/program',
		component: require('./views/Program/index').default
	},
	{
		path: '/project',
		component: require('./views/Project/index').default,
	},
	{
		path: '/project/:id/detail',
		component: require('./views/Scope/index').default		
	},
	{
		path: '/project/:id/scope/:crid',
		component: require('./views/Scope/UserStory').default		
	},
	{
		path: '/tasks',
		component: require('./views/Tasks/index').default		
	},
	{
		path: '/company',
		component: require('./views/Company/index').default		
	},
	{
		path: '/acl',
		component: require('./views/ACL/index').default		
	},
	{
		path: '/mytasks',
		component: require('./views/Tasks/user').default
	},
	{
		path: '/users',
		component: require('./views/User/index').default
	},

	// {
	// 	path: '/login',
	// 	component: require('./views/Login').default
	// },
];

export default new VueRouter({
	routes
});