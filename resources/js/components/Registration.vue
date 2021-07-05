<template>
    <div class="user-registration" v-loading="blockUI" v-cloak>
        <div class="card">
            <div class="card-header">
               <h1 class="font-weight-bold">Sign Up.</h1>
               <p class="mb-0">Register for our platform</p>
            </div>
            <div class="card-body">
                <el-form :model="form.data" :rules="form.rules" label-position="top" ref="registerForm">
                   <el-row :gutter="20">
                        <el-col :md="12" :xs="24">
                            <el-form-item label="First Name" prop="firstName">
                                <el-input placeholder="e.g. John" v-model="form.data.firstName"></el-input>
                                <small class="text-danger" v-if="form.errors.firstName">{{ form.errors.firstName[0]}}</small>
                            </el-form-item>
                        </el-col>
                        <el-col :md="12" :xs="24">
                            <el-form-item label="Last Name" prop="lastName">
                                <el-input placeholder="e.g. Doe" v-model="form.data.lastName"></el-input>
                                <small class="text-danger" v-if="form.errors.lastName">{{ form.errors.lastName[0]}}</small>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="20">
                        <el-col :span="24">
                            <el-form-item label="Email Address" prop="email">
                                <el-input placeholder="e.g. johndoe@xyz.com" v-model="form.data.email"></el-input>
                                <small class="text-danger" v-if="form.errors.email">{{ form.errors.email[0]}}</small>
                            </el-form-item>
                        </el-col>
                   </el-row>
                    <el-form-item label="Password" prop="password">
                        <el-input class="password-input" :type="passwordType" v-model="form.data.password">
                            <template slot="append">
                                <div class="pwd-show" @click="togglePasswordVisibility">
                                    <i class="fas fa-eye" v-if="passwordType == 'password'"></i>
                                    <i class="fas fa-eye-slash" v-if="passwordType == 'text'"></i>
                                </div>
                            </template>
                        </el-input>
                        <small class="d-block m-0 mt-2 text-muted" style="line-height:14px;">Between 6 and 22 characters long with at least one lowercase, uppercase, number, and symbol.</small>
                        <small class="text-danger" v-if="form.errors.password">{{ form.errors.password[0]}}</small>
                    </el-form-item>
                    <el-button class="my-4" icon="far fa-user mr-2" type="primary" @click="submitForm('registerForm')">Create Account</el-button>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
   data() {
      var passwordMatch = (rule, value, callback) => {
         if (value !== this.form.data.password) {
          callback(new Error('Password don\'t match!'));
         } else {
          callback();
         }
      };
      return {
            blockUI : false,
            form : {
                data : {
                firstName : '',
                lastName : '',
                email : '',
                password: '',
                password_confirmation : '',
                },
                errors : [],
                rules : {
                    firstName: [{ required: true, message: 'The first name field is required.', trigger: 'blur' }],
                    email: [
                        { required: true, message: 'The email field is required.', trigger: 'blur' },
                        { pattern: /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/, message: 'The email field does not seems to be a valid email.', trigger: 'blur' }
                    ],
                    password: [
                        { required: true, message: 'Password is required', trigger: "blur" },
                        { min: 6, message: 'Password should be minimum 6 characters', trigger: "blur" },
                        { max: 22, message: 'Password should be max 22 characters', trigger: "blur" },
                        { pattern: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/, message: 'The password must contain at least: 1 uppercase letter, 1 lowercase letter, 1 number, and one special character (E.g. , . _ & ? etc)', trigger: "blur"}
                    ],
                    password_confirmation: [
                        { required: true, message: 'Password is required', trigger: "blur" },
                        { validator: passwordMatch, trigger: 'blur' },
                    ]
                },
            },
            passwordType : 'password'
        };
    },
    created() {
        var vm = this;
    },
    methods: {
        togglePasswordVisibility() {
            var vm = this;
            vm.passwordType = vm.passwordType == 'password' ? 'text' : 'password';
        },
        submitForm(formName) {
            var vm = this;
            vm.$refs[formName].validate((valid) => {
                if (valid) {
                    vm.blockUI = true;
                    vm.form.data._token = document.head.querySelector('meta[name="csrf-token"]');
                    vm.form.data.next = vm.next;
                    vm.form.data.session_key = vm.session_key;
                    axios.post("/register", vm.form.data).then(function(response) {
                        vm.$notify({type: "success", title: 'Success', duration: 1000, message: 'Registration Successful.. Redirection now' , onClose: function() { 
                            window.location = '/';
                        }});
                    })
                    .catch(function(error) {
                        console.log(error.response.data.message);
                        vm.form.errors = error.response.data.errors;
                        vm.blockUI = false;
                        vm.$notify({ type: "error", title: 'Input Error', message: 'Please fix the errors displayed inline for each fields'});
                        window.scrollTo({top: 0, left: 0, behavior: 'smooth'});
                    });  
                }
                else {
                    vm.$notify({ type: "error", title: 'Input Error', message: 'Please fix the errors displayed inline for each fields'});
                    window.scrollTo({top: 0, left: 0, behavior: 'smooth'});
                }
            });
        },
    },
};
</script>
