<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        I'm an example component.
						<button class="btn btn-danger" v-on:click="wtf"> Test </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
		
		methods: {
		    //function to switch CSS to show/hide HighLighted Errors text
		    wtf: function () {
		        alert('77');
			}
		},
    }
</script>
