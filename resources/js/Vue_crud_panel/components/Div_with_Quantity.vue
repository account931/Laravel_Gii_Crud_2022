<template>
    <div class="col-sm-12 col-xs-12 alert alert-info borderX">
		Aricles found(Vue):  <span class="badge badge-danger" <b> {{  this.$store.state.posts.length }} </b> </span>
		</br>
		<button class="btn btn-danger" style="padding:0.7em 2em;" v-on:click="wtf"> Test </button>
	</div>
</template>

<script>
export default {
  name: 'Div_with_Quantity',
  data() {
    return {
      //postDialogVisible: false,
    };
  },
  
  //computed property is used to declaratively describe a value that depends on other values. When you data-bind to a computed property inside the template, Vue knows when to update the DOM when any of the values depended upon by the computed property has changed.
  computed: { 
  },
  beforeMount() {
  },
  methods: {
  		
		//function to ...
		wtf: function () {
		    alert('77');
		},
  },
}
</script>