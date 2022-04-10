
<template>
	<div class="blog">
		<h1>{{title}} number {{this.currentDetailID}} </h1>
		<div class="row">
			Edit me here {{this.currentDetailID}}  
		</div>
		<br><br>
	</div>
	
</template>

<script>
	export default{
		name:'edit',
		data (){
			return{
				title:'Edit',
				currentDetailID: 1, 
			}
		},
		
		//before mount
        beforeMount() {
            //console.log(this.$store.state.posts);
	        //getting route ID => e.g "wpBlogVueFrameWork#/details/2", gets 2. {Pid} is set in 'pages/home' in => this.$router.push({name:'details',params:{Pid:proId}})
	        var ID = this.$route.params.Pidd; //gets 2
	        //ID = ID - 1; //to comply with Vuex Store array, that starts with 0
	        this.currentDetailID = ID; //set to this.state
    },
	}
</script>

<style scoped>
	
</style>