<template>
    <div class="content">
        <div class="row">
            <div class="col-4">
                <p v-if="searchUserText.length !== 0"><b>Выполняеться поиск.....</b></p>
                <p v-else><b>Страница</b> {{ currentPage }} / {{ InfoCount }}</p>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <input type="search" class="form-control" placeholder="Поиск по фамилии" v-model="searchUserText">
                </div>
            </div>
        </div>
        <div class="row mb-2">
                <div class="col-1">
                    <button class="btn btn-control" @click="prevPage"><span class="fa fa-mail-reply"></span></button>
                </div>
                <div class="offset-10 col-1 text-right">
                    <button class="btn btn-control" @click="nextPage"><span class="fa fa-mail-forward"></span></button>
                </div>
        </div>
        <!--Таблица записей-->
        <div class="table-responsive">
            <table class="table">
                <tr class="text-center">
                    <th>Создан</th>
                    <th>Имя</th>
                    <th>Руководитель подразделения</th>
                    <th>Должность</th>
                    <th>Подразделение</th>
                    <th>Период</th>
                </tr>
                <tr v-for="(resource, index) in InfoResource" :key="resource.id" class="row-info" @click="getInfoResource(resource.id, index)" :class="{'row-info-select': currentIndex === index}">
                    <td>{{ dateFormat(resource.created_at) }}</td>
                    <td>{{ resource.name }}</td>
                    <td>{{ resource.leader }}</td>
                    <td>{{ resource.function }}</td>
                    <td>{{ resource.unit }}</td>
                    <td>{{ dateFormat(resource.perStart) }} - {{ dateFormat(resource.perEnd) }}</td>
                </tr>
            </table>
        </div>
        <!--Подробно-->
        <div class="resource-info" v-if="resource.name" @click="closeInfoResource">
            <div class="form" @click.stop="">
                <div class="form-close">
                    <button class="btn btn-danger" @click="closeInfoResource">X</button>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p><b>Сотрудник:</b> {{ resource.name }}</p>
                    </div>
                    <div class="col-6">
                        <p><b>Доступ</b> с {{ dateFormat(resource.perStart) }} по {{ dateFormat(resource.perEnd) }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p><b>Заявка создана:</b> {{ dateFormat(resource.created_at) }}</p>
                    </div>
                    <div class="col-6">
                        <p>
                            <a :href="resource.pdf_url" download>Скачать PDF файл</a> || <a href="#" @click.prevent="showPDF = !showPDF">
                            <span v-if="!showPDF">Просмотреть PDF</span>
                            <span v-else>Закрыть PDF</span>
                            </a>
                        </p>
                    </div>
                </div>
                <div v-if="resource.pdf_url">
                    <embed class="pdf-view" :src="resource.pdf_url" v-if="showPDF"/>
                </div>
                <div class="row" v-if="!showPDF">
                    <div class="offset-6 col-6 text-right mb-4">
                        <button class="btn" @click="findUserAd">Полная информация о пользователе</button>
                    </div>
                </div>
                <div v-if="!showPDF">
                    <!--Полное имя-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-user-md"></span>&nbsp;Полное имя</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.username" ref="userName">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userName)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Логин-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-font-awesome"></span>&nbsp;Логин</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.login" ref="userLogin">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userLogin)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Почта-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-mail-reply-all"></span>&nbsp;Почта</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.email" ref="userMail">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userMail)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Телефон-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-phone"></span>&nbsp;Телефон</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.phone" ref="userPhone">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn"  @click.prevent="copyPasswordBuffer($refs.userPhone)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Мобильный-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-phone-square"></span>&nbsp;Мобильный</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.mobile" ref="userMobile">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn"  @click.prevent="copyPasswordBuffer($refs.userMobile)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Доп. телефон-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-phone-square"></span>&nbsp;Доп. телефон</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.othertelephone" ref="userOtherphone">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userOtherphone)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Отдел-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-address-card"></span>&nbsp;Отдел</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.department" ref="userDepartment">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userDepartment)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Компания-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-address-card"></span>&nbsp;Компания</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.company" ref="userCompany">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userCompany)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Город-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-archive"></span>&nbsp;Город</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.city" ref="userCity">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userCity)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Улица-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-address-book"></span>&nbsp;Улица</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.street" ref="userStreet">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userStreet)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                    <!--Должность-->
                    <div class="row offset-1">
                        <div class="col-3">
                            <p><span class="fa fa-ticket"></span>&nbsp;Должность</p>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="text" class="form-control" :value="fullUser.title" ref="userTitle">
                            </div>
                        </div>
                        <div class="col-1" title="Копировать">
                            <button class="btn" @click.prevent="copyPasswordBuffer($refs.userTitle)"><span class="fa fa-copy"></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import Sound from "../../assets/js/Sound";
    export default {
        name: "ShowInfoResource",
        data(){
            return {
                isLoading: false,
                currentIndex: -1,
                resources : [],
                currentPage: 1,
                lengthItemOnPage:25,
                count: 0,
                resource: {},
                showPDF: false,
                fullUser: {},
                searchUserText: '',
                countSearchUser: 0
            }
        },
        computed: {
            InfoResource() {

                if (this.searchUserText.length > 0)
                {
                    return this.resources.filter((item) => item.name.toLowerCase().indexOf(this.searchUserText.toLowerCase()) !== -1);
                }

                return this.resources.filter((item, index) => {
                    let start = (this.currentPage - 1) * this.lengthItemOnPage;
                    let end = this.currentPage * this.lengthItemOnPage;

                    if (index >= start && index < end) return true;
                });
            },
            InfoCount() {
                return Math.ceil(this.resources.length / this.lengthItemOnPage);
            }
        },
        methods: {
            ...mapActions(['setMessenger', 'setLoaderBar']),
            getInfoResources(){
                this.isLoading = true;
                this.setLoaderBar(true);

                axios.get('/api/resource-access')
                    .then(response => {
                        this.count = response.data.count;
                        this.resources = response.data.resources;
                    })
                    .catch(error => {
                        Sound.playSound('/sounds/_alert.mp3');
                        this.errors = error.response.data.message;
                        this.setMessenger({text: this.errors, status: 'error'});
                    })
                    .finally(() => {
                        this.isLoading = false;
                        this.setLoaderBar(false);
                    });
            },
            getInfoResource(resourceId, index){
                this.fullUser = {};

                this.isLoading = true;
                this.setLoaderBar(true);

                axios.get(`/api/resource-access/${ resourceId }`)
                    .then(response => {

                        this.resource = response.data.resource;
                        this.currentIndex = index;

                    })
                    .catch(error => {
                        Sound.playSound('/sounds/_alert.mp3');
                        this.errors = error.response.data.message;
                        this.setMessenger({text: this.errors, status: 'error'});
                    })
                    .finally(() => {
                        this.isLoading = false;
                        this.setLoaderBar(false);
                        document.body.style.overflow = 'hidden';
                    });
            },
            closeInfoResource(){
                this.resource = {};
                document.body.style.overflow = '';
            },
            dateFormat(dateTime){
                return new Date(dateTime).toLocaleDateString();
            },
            prevPage() {
                if (this.currentPage > 1) {
                    this.currentPage -= 1;

                    this.currentIndex = -1;
                }
            },
            nextPage() {
                if ((this.currentPage * this.lengthItemOnPage) < this.resources.length) {
                    this.currentPage += 1;

                    this.currentIndex = -1;
                }
            },
            listenerKeyDown(e) {
                if (e.code === 'ArrowRight' && e.key === 'ArrowRight') {
                    this.nextPage();
                }
                else if (e.code === 'ArrowLeft' && e.key === 'ArrowLeft') {
                    this.prevPage();
                }
            },
            findUserAd(){
                this.isLoading = true;
                this.setLoaderBar(true);
                axios.get(`/api/users/find/${ this.resource.name }`)
                    .then(response => {
                        this.users = response.data.users;
                        if(this.users.length === 1){
                            this.fullUser = this.users[0];
                        }
                    })
                    .catch(error => {
                        Sound.playSound('/sounds/_alert.mp3');
                        this.errors = error.response.data.message;
                        this.setMessenger({text: this.errors, status: 'error'});
                    })
                    .finally(() => {
                        this.isLoading = false;
                        this.setLoaderBar(false);
                    });
            },
            copyPasswordBuffer(input){
                input.select();
                document.execCommand('copy');
            },
        },
        created() {
            this.getInfoResources();
            document.addEventListener('keydown', this.listenerKeyDown);
        },
        beforeDestroy() {
            document.body.removeEventListener('keydown', this.listenerKeyDown);
        }
    }
</script>

<style lang="scss" scoped>
    .row-info{
        cursor: pointer;
        &:hover{
          background: #d0d0d0;
        }
        &:active{
            background: #afafaf;
        }
        &.row-info-select{
            background: #96afca;
            color: white;
        }
    }
    .pdf-view{
        width: 100%;
        height: 750px;
        /*height: 1100px;*/
    }
    .resource-info{
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1000;
        user-select: none;
        background: rgba(31, 33, 34, 0.79);
        overflow: auto;
        display: flex;
        .form{
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            position: relative;
            align-self: center;
            .form-close{
                position: absolute;
                right: 11px;
                top: 6px;
                z-index: 1;
            }
        }
    }
</style>
