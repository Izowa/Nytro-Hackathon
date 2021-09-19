import Vue from 'vue'
import VueRouter from 'vue-router'
import NewPosts from '@/views/feed/NewPosts.vue'
import NotFound from '@/views/NotFound.vue'
import Meta from 'vue-meta'

Vue.use(VueRouter)
Vue.use(Meta)

const routes = [
  // ? The order of index.js should match that of the /views folder
  {
    path: '/', // Have the main page of the site redirect to the newPosts page
    redirect: '/feed'
  },
  {
    path: '/feed',
    name: 'Feed',
    component: NewPosts
  },
  {
    path: '/feed/featured-posts',
    name: 'FeaturedPosts',
    component: () => import('../views/feed/FeaturedPosts.vue')
  },
  {
    path: '/feed/top-posts',
    name: 'TopPosts',
    component: () => import('../views/feed/TopPosts.vue')
  },
  {
    path: '/settings',
    name: 'Account',
    component: () => import('../views/Account.vue')
  },
  {
    path: '/buy-nyaz',
    name: 'BuyNyaz',
    component: () => import('../views/BuyNyaz.vue')
  },
  {
    path: '/change-password/:auth',
    name: 'ChangePassword',
    component: () => import('../views/ChangePassword.vue')
  },
  {
    path: '/create-new-post',
    name: 'CreateNewPost',
    component: () => import('../views/CreateNewPost.vue')
  },
  {
    path: '/link-nyzo',
    name: 'LinkNyzo',
    component: () => import('../views/LinkNyzo.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/post/:id',
    name: 'Post',
    component: () => import('../views/Post.vue')
  },
  {
    path: '/my-posts',
    name: 'MyPosts',
    component: () => import('../views/MyPosts.vue')
  },
  {
    path: '/profile/:uID',
    name: 'Profile',
    component: () => import('../views/Profile.vue')
  },
  {
    path: '/password-request',
    name: 'PwdRequest',
    component: () => import('../views/PwdRequest.vue')
  },
  {
    path: '/feed/search/:searchTerm',
    name: 'Search',
    component: () => import('../views/Search.vue')
  },
  {
    path: '/signup',
    name: 'SignUp',
    component: () => import('../views/SignUp.vue')
  },
  // Catch All 404
  {
    path: '/:catchAll(.*)',
    name: 'NotFound',
    component: NotFound
  }
]

const router = new VueRouter({
  routes
})

export default router

