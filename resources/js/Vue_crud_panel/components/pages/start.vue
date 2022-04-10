
<template>
	<div class="blog">
		<h1>{{title}}</h1>
		<div class="row">
		   <router-link class="nav-link" to="/blog"> <button>  Go to Blog </button> </router-link>
		</div>
		<br><br>
	</div>
	
</template>

<script>
	export default{
		name:'edit',
		data (){
			return{
				title:'Start page'
			}
		}
	}
</script>

<style scoped>
	
</style>