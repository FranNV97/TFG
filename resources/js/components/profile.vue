<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header titulo">
                        <div class="row">
                            <div class="col-sm-12">
                                Perfil

                                <div class="float-right">
                                    <button class="btn btn-warning textoTable" v-if="!editable" v-on:click="formEditUser()">Editar perfil <i class="fa fa-pencil"></i></button>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="card-body">
                        <div v-if="!editable" class="profile">
                            <p class="textoTable"><b>Email:</b> {{ user.email }}</p>
                            <p class="textoTable" v-if="user.name != null"><b>Nombre:</b> {{ user.name }}</p>
                            <p class="textoTable" v-if="user.surname != null"><b>Apellidos:</b> {{ user.surname }}</p>
                            <p class="textoTable" v-if="user.address != null"><b>Dirección:</b> {{ user.address }}</p>
                            <p class="textoTable" v-if="user.bdate != null"><b>Fecha de nacimiento:</b> {{ user.bdate }}</p>
                        </div>

                        <div v-else class="profile">
                            <div class="form-group">
                                <label class="col-md-12 textoTable">Email</label>
                                <div class="col-md-12 pocoMargenBajo">
                                    <input type="email" class="form-control form-control-line textoTable"
                                    v-model="user.email">
                                    </div>
                                </div>

                                <label class="col-md-12 textoTable">Nombre</label>
                                <div class="col-md-12 pocoMargenBajo">
                                    <input type="text" class="form-control form-control-line textoTable"
                                    v-model="user.name">
                                    </div>

                                <label class="col-md-12 textoTable">Apellidos</label>
                                <div class="col-md-12 pocoMargenBajo">
                                    <input type="text" class="form-control form-control-line textoTable"
                                    v-model="user.surname">
                                </div>

                                <label class="col-md-12 textoTable">Dirección</label>
                                <div class="col-md-12 pocoMargenBajo">
                                    <input type="text" class="form-control form-control-line textoTable"
                                    v-model="user.address">
                                </div>

                                <label class="col-md-12 textoTable">Fecha de nacimiento</label>
                                <div class="col-md-12">
                                    <input type="date" class="form-control form-control-line textoTable"
                                    v-model="user.bdate">

                                </div>

                                <div class="col-sm-12 margenArriba">
                                    <button class="btn btn-success textoTable"
                                    v-on:click="editUser()">Actualizar
                                    </button>
                                    <button class="btn btn-danger textoTable"
                                    v-on:click="hideFormEditUser()">Cancelar
                                    </button>
                                </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
module.exports = {
  props: ["usuario"],

  data() {
    return {
      user: {},
      editable: false
    };
  },

  methods: {
    editUser() {
      let data = {
          name: this.$data.user.name,
          surname: this.$data.user.surname,
          address: this.$data.user.address,
          bdate: this.$data.user.bdate,
          email: this.$data.user.email
      }

      var URL = "/users/" + this.$data.user.id;

      axios.put(URL, data).then(response => {
        Swal.fire("Hecho", "Tu perfil ha sido actualizado", "success");
      });

      this.editable = false;
    },

    formEditUser() {
      this.editable = true;
    },
    hideFormEditUser() {
      this.editable = false;
    }
  },

  mounted() {
    this.user = this.usuario;
  }
};
</script>
