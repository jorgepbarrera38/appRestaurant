<template>
    <div class="card" id="card">
        <div class="card-header">
            Usuarios del sistema 
            <div class="float-right">
                <template>
                    <button class="btn btn-primary btn-sm" v-bind:class="pagination.currentPage == 1 ? 'disabled':''" v-on:click="pagination.currentPage-=1; getUsers()">&lt</button>
                    <button class="btn btn-primary btn-sm" v-bind:class="pagination.currentPage == pagination.lastPage ? 'disabled':''" v-on:click="nextPage()">&gt</button>
                </template>
                <button v-on:click="addUser()" class="btn btn-success btn-sm">Nuevo</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <th nowrap>Nombre de usuario</th>
                        <th nowrap>Nombres completos</th>
                        <th>Rol</th>
                        <th>Acción</th>
                    </thead>
                    <tbody>
                        <tr v-for="user in users">
                            <td nowrap>{{ user.name }}</td>
                            <td nowrap>{{ user.names }}</td>
                            <td nowrap>{{ user.role }}</td>
                            <td nowrap>
                                <button class="btn btn-warning btn-sm" v-on:click="editUser(user.id)">Editar</button>
                                <button class="btn btn-sm btn-danger" v-on:click="deleteUser(user.id)">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!--MOdal add User-->
        <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors">
                        <li v-for="error in errors">{{ error[0] }}</li>
                    </div>
                    <form v-on:submit.prevent="saveUser()">
                        <div class="form-group">
                            <label for="">Nombres completos</label>
                            <input type="text" class="form-control" v-model="user.names">
                        </div>
                        <div class="form-group">
                            <label for="">Nombre de usuario</label>
                            <input type="text" class="form-control" v-model="user.name">
                        </div>
                        <div class="form-group">
                            <label for="">Contraseña</label>
                            <input type="password" class="form-control" v-model="user.password">
                        </div>
                        <div class="form-group">
                            <label for="">Rol</label>
                            <select v-model="user.role" class="form-control">
                                <option value="">Seleccione un rol</option>
                                <option value="administrador">Administrador</option>
                                <option value="vendedor">Vendedor</option>
                            </select>
                        </div>
                        <button style="display:none" type="submit">Guardar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button v-on:click="closeModalNewUser()" class="btn btn-danger">Cancelar</button>
                    <button type="button" v-on:click.prevent="saveUser()" class="btn btn-primary">Guardar</button>
                </div>
                </div>
            </div>
        </div>
        <!--End MODAL Add User-->
        <!--MOdal Editar User-->
        <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors">
                        <li v-for="error in errors">{{ error[0] }}</li>
                    </div>
                    <form v-on:submit.prevent="updateUser()">
                        <div class="form-group">
                            <label for="">Nombres completos</label>
                            <input type="text" class="form-control" v-model="user.names">
                        </div>
                        <div class="form-group">
                            <label for="">Nombre de usuario</label>
                            <input type="text" class="form-control" v-model="user.name">
                        </div>
                        <div class="form-group">
                            <label for="">Contraseña</label>
                            <input type="password" class="form-control" v-model="user.password" placeholder="********">
                        </div>
                        <div class="form-group">
                            <label for="">Rol</label>
                            <select v-model="user.role" class="form-control">
                                <option value="">Seleccione un rol</option>
                                <option value="administrador">Administrador</option>
                                <option value="vendedor">Vendedor</option>
                            </select>
                        </div>
                        <button style="display:none" type="submit">Guardar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button v-on:click="closeModalEditUser()" class="btn btn-danger">Cancelar</button>
                    <button type="button" v-on:click.prevent="updateUser()" class="btn btn-primary">Actualizar</button>
                </div>
                </div>
            </div>
        </div>
        <!--End MODAL Edit User-->
    </div>
</template>
<script>
    import toastr from 'toastr';
    export default {
        name:'UserComponent',
        mounted(){
            this.getUsers();
        },
        data(){
            return {
                user:{
                    id:'',
                    names:'',
                    name:'',
                    password:'',
                    role:''
                },
                errors:'',
                users:'',
                pagination:{
                    currentPage:1,
                    lastPage:1
                }
            }
        },
        methods:{
            getUsers:function(){
                if(this.pagination.currentPage <= this.pagination.lastPage){
                    axios.get('users?page='+this.pagination.currentPage).then(response=>{
                        this.users = response.data.data;
                        this.pagination.currentPage = response.data.current_page;
                        this.pagination.lastPage = response.data.last_page;
                    });
                }
            },
            nextPage: function(){
                if(this.pagination.currentPage < this.pagination.lastPage){
                    this.pagination.currentPage+=1; 
                    this.getUsers();
                }
            },
            addUser: function(){
                this.cleanFields();
                $('#addUserModal').modal('show');
            },
            saveUser: function(){
                axios.post('users', this.user).then(response=>{
                    this.getUsers();
                    this.closeModalNewUser();
                    toastr.success('Usuario creado con éxito');
                }).catch(errors=>{
                    this.errors = errors.response.data.errors;
                });
            },
            closeModalNewUser: function(){
                $('#addUserModal').modal('hide');
                this.cleanFields();
            },
            cleanFields: function(){
                this.errors = '';
                this.user.id = '';
                this.user.names = '';
                this.user.name = '';
                this.user.password = '';
                this.user.role = '';
            },
            deleteUser: function(userId){
                axios.delete('users/'+userId).then(response=>{
                    this.getUsers();
                    toastr.success('Usuario eliminado con éxito');
                }).catch(errors=>{
                    toastr.error(errors.response.data.msg);
                });
            },
            editUser: function(userId){
                axios.get('users/'+userId).then(response=>{
                    console.log(response.data);
                    this.user.id = response.data.id;
                    this.user.names = response.data.names;
                    this.user.name = response.data.name;
                    this.user.role = response.data.role;
                    $('#editUserModal').modal('show');
                });
            },
            closeModalEditUser: function(){
                $('#editUserModal').modal('hide');
                this.cleanFields();
            },
            updateUser: function(){
                axios.put('users/'+this.user.id, this.user).then(response=>{
                    this.getUsers();
                    toastr.success('Usuario actualizado exitosamente');
                    this.closeModalEditUser();
                    this.cleanFields();
                });
            }
        }
    }
</script>
<style>
    #card{
        height: 415px;
    }
</style>
