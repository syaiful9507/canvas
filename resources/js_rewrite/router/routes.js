import AllStats from '../views/AllStats';

export default [
    {
        path: '/',
        name: 'home',
        component: AllStats,
    },
    {
        path: '*',
        name: 'catch-all',
        redirect: '/',
    },
];
