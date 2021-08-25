<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Mensagens</div>

                    <div class="card-body">
                        <div>
                            <h5 class="alert alert-info" v-if="messages.length === 0">Nenhuma mensagem</h5>
                            <p v-for="(message,index) in messages" :key="index">
                                {{ message.content }}
                                <br>
                                <small>{{ message.created_at }}</small>
                            </p>
                        </div>

                        <br>
                        <hr>
                        <div>
                            <form @submit.prevent="handleSubmit">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>Mensagem</label>
                                            <textarea class="form-control" v-model="fields.content" required></textarea>
                                            <div v-if="errors && errors.body" class="text-danger">{{ errors.body[0] }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <br><br>
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            messages: [],
            fields: {},
            errors: {},
        }
    },
    mounted() {
        Echo.channel('chat').listen('SendMessage', (e) => {
            // console.log(e);
            this.messages.push(e);
        })
            .error((error) => {
                console.error(error);
            });
    },
    created() {
        axios.get('/messages')
            .then(response => {
                if (this.messages.length === 0) {
                    this.messages = response.data.messages;
                }
            });
    },
    methods: {
        handleSubmit() {
            axios.post('/messages/store', this.fields)
                .then(response => (
                    this.fields = {} // clear fields
                ))
                .catch(error => {
                    // Exibe erros do backend
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
        }
    }
}
</script>
