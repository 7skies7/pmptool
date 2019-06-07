import VueRouter from 'vue-router'

var routes = [
	{
		path: '/dashboard',
		component: require('./views/Dashboard').default
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
		path: '/project/update',
		component: require('./views/Project/update').default		
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
		component: require('./views/Acl/index').default		
	},

	// {
	// 	path: '/login',
	// 	component: require('./views/Login').default
	// },
];

export default new VueRouter({
	routes
});