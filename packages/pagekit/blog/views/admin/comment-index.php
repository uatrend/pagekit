<?php $view->style('comment-index', 'blog:assets/css/blog.admin.css') ?>
<?php $view->script('comment-index', 'blog:app/bundle/comment-index.js', ['vue']) ?>

<div id="comments" v-cloak>

    <div class="uk-margin uk-flex uk-flex-between uk-flex-wrap">
        <div class="uk-flex uk-flex-middle uk-flex-wrap">

            <h2 class="uk-margin-remove" v-if="!selected.length">{{ '{0} %count% Comments|{1} %count% Comment|]1,Inf[ %count% Comments' | transChoice(count, {count:count}) }}</h2>

            <template v-else>
                <h2 class="uk-margin-remove">{{ '{1} %count% Comment selected|]1,Inf[ %count% Comments selected' | transChoice(selected.length, {count:selected.length}) }}</h2>

                <div class="uk-margin-left">
                    <ul class="uk-subnav pk-subnav-icon">
                        <li><a class="pk-icon-check pk-icon-hover" :title="'Approve' | trans" uk-tooltip="delay: 500" @click="status(1)"></a></li>
                        <li><a class="pk-icon-block pk-icon-hover" :title="'Unapprove' | trans" uk-tooltip="delay: 500" @click="status(0)"></a></li>
                        <li><a class="pk-icon-spam pk-icon-hover" :title="'Mark as spam' | trans" uk-tooltip="delay: 500" @click="status(2)"></a></li>
                        <li><a class="pk-icon-delete pk-icon-hover" :title="'Delete' | trans" uk-tooltip="delay: 500" @click.prevent="remove"></a></li>
                    </ul>
                </div>
            </template>

            <div class="uk-search uk-search-default pk-search">
                <span uk-search-icon></span>
                <input class="uk-search-input" type="search" v-model="config.filter.search" debounce="300">
            </div>

        </div>
    </div>

    <div class="uk-overflow-auto">

        <table class="uk-table uk-table-middle uk-table-hover pk-table-large">
            <thead>
                <tr>
                    <th class="pk-table-width-minimum"><input class="uk-checkbox" type="checkbox" v-check-all:selected="{ selector: 'input[name=id]' }" number></th>
                    <th class="pk-table-min-width-300" colspan="2">{{ 'Comment' | trans }}</th>
                    <th class="pk-table-width-100 uk-text-center">
                        <input-filter :title="$trans('Status')" :value.sync="config.filter.status" :options="statusOptions" v-model="config.filter.status"></input-filter>
                    </th>
                    <th class="pk-table-width-200" :class="{'pk-filter': config.post, 'uk-active': config.post}">
                        <span v-if="!config.post">{{ 'Post' | trans }}</span>
                        <span v-else>{{ config.post.title }}</span>
                    </th>
                </tr>
            </thead>
            <tbody >

            <template v-for="comment in orderBy(comments, 'created', -1)">
                <template v-if="editComment.id !== comment.id">
                    <tr class="check-item" :class="{'uk-active': active(comment)}" v-for="post in filterBy(posts, comment.post_id, 'id')">

                        <td class="pk-blog-comments-padding"><input class="uk-checkbox" type="checkbox" name="id" :value="comment.id"></td>
                        <td class="pk-table-width-minimum">
                            <img class="uk-img-preserve uk-border-circle" width="40" height="40" :alt="comment.author" v-gravatar="comment.email">
                        </td>
                        <td class="uk-visible-toggle">

                            <div class="uk-margin uk-flex uk-flex-between uk-flex-wrap">
                                <div>
                                    <a :href="$url.route('admin/user/edit', { id: comment.user_id })" v-if="comment.user_id!=0">{{ comment.author }}</a>
                                    <span v-else>{{ comment.author }}</span>
                                    <br><a class="uk-link-muted" :href="'mailto:'+comment.email">{{ comment.email }}</a>
                                </div>
                                <div class="uk-flex uk-flex-middle">
                                    <ul class="uk-subnav pk-subnav-icon uk-hidden-hover uk-margin-right">
                                        <li><a class="pk-icon-edit pk-icon-hover" :title="'Edit' | trans" uk-tooltip="delay: 500" @click.prevent="edit(comment)"></a></li>
                                        <li><a class="pk-icon-reply pk-icon-hover" :title="'Reply' | trans" uk-tooltip="delay: 500" @click.prevent="reply(comment)"></a></li>
                                    </ul>

                                    <a class="uk-link-muted" v-if="post.accessible" :href="$url.route(post.url.substr(1))+'#comment-'+comment.id">{{ comment.created | relativeDate }}</a>
                                    <span v-else>{{ comment.created | relativeDate }}</span>
                                </div>
                            </div>

                            <div v-html="comment.content"></div>

                            <div class="uk-margin-top" v-if="replyComment.parent_id === comment.id">
                                <!-- <form v-validator="replyform" @submit.prevent="submit | valid"> -->
                                <form @submit.prevent="submit">

                                    <div class="uk-margin">
                                        <label for="form-content" class="uk-form-label">{{ 'Content' | trans }}</label>
                                        <div class="uk-form-controls">
                                            <textarea id="form-content" class="uk-form-width-large uk-textarea" name="content" rows="10" v-model="replyComment.content" v-validate="'required'"></textarea>
                                            <div class="uk-text-small uk-text-danger" v-show="errors.first('content')">{{ 'Content cannot be blank.' | trans }}</div>
                                        </div>
                                    </div>

                                    <p>
                                        <button class="uk-button uk-button-primary" type="submit">{{ 'Reply' | trans }}</button>
                                        <button class="uk-button uk-button-default" @click.prevent="cancel">{{ 'Cancel' | trans }}</button>
                                    </p>

                                </form>
                            </div>

                        </td>
                        <td class="pk-blog-comments-padding uk-text-center">
                            <a href="#" :title="getStatusText(comment)" :class="{'pk-icon-circle-success': comment.status == 1, 'pk-icon-circle-warning': comment.status == 0, 'pk-icon-circle-danger':  comment.status == 2}" @click="toggleStatus(comment)">
                            </a>
                        </td>
                        <td class="pk-blog-comments-padding">
                            <a :href="$url.route('admin/blog/post/edit', { id: post.id })">{{ post.title }}</a>

                            <p>
                                <a class="uk-text-nowrap" :class="{'pk-link-icon': !post.comments_pending}" :href="$url.route('admin/blog/comment', { post: post.id })" :title="'{0} No pending|{1} One pending|]1,Inf[ %comments_pending% pending' | transChoice(post.comments_pending, post)"><i class="pk-icon-comment" :class="{'pk-icon-primary': post.comments_pending}"></i> {{ post.comment_count }}</a>
                            </p>
                        </td>

                    </tr>
                </template>
                <template v-else>
                    <tr>

                        <td></td>
                        <td class="pk-table-width-minimum">
                            <img class="uk-img-preserve uk-border-circle" width="40" height="40" :alt="editComment.author" v-gravatar="editComment.email">
                        </td>
                        <td colspan="3">
                            <!-- <form class="uk-form-stacked" v-validator="editform" @submit.prevent="submit | valid"> -->
                            <form class="uk-form-stacked" @submit.prevent="submit">

                                <div class="uk-margin">
                                    <label for="form-author" class="uk-form-label">{{ 'Name' | trans }}</label>
                                    <div class="uk-form-controls">
                                        <input id="form-author" class="uk-form-width-large uk-input" name="author" type="text" v-model="editComment.author" v-validate="'required'">
                                        <div class="uk-text-small uk-text-danger" v-show="errors.first('author')">{{ 'Author cannot be blank.' | trans }}</p>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <label for="form-email" class="uk-form-label">{{ 'E-mail' | trans }}</label>
                                    <div class="uk-form-controls">
                                        <input id="form-email" class="uk-form-width-large uk-input" name="email" type="text" v-model.lazy="editComment.email" v-validate="'required|email'">
                                        <div class="uk-text-small uk-text-danger" v-show="errors.first('email')">{{ 'Field must be a valid email address.' | trans }}</p>
                                    </div>
                                </div>
                                <div class="uk-margin">
                                    <label for="form-status" class="uk-form-label">{{ 'Status' | trans }}</label>
                                    <div class="uk-form-controls">
                                        <select id="form-status" class="uk-form-width-large uk-select" v-model="editComment.status">
                                            <option v-for="(status, status_key) in statuses" :key="status_key" :value="status_key">{{ status }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="uk-margin">
                                    <label for="form-content" class="uk-form-label">{{ 'Status' | trans }}</label>
                                     <div class="uk-form-controls">
                                        <textarea id="form-content" class="uk-form-width-large uk-textarea" name="content" rows="10" v-model="editComment.content" v-validate="'required'"></textarea>
                                         <div class="uk-text-small uk-text-danger" v-show="errors.first('content')">{{ 'Content cannot be blank.' | trans }}</div>
                                    </div>
                                </div>

                            <p>
                                <button class="uk-button uk-button-primary" type="submit">{{ 'Save' | trans }}</button>
                                <button class="uk-button uk-button-secondary" @click.prevent="cancel">{{ 'Cancel' | trans }}</button>
                            </p>

                            </form>
                        </td>

                    </tr>
                </template>
            </template>

            </tbody>
        </table>
    </div>

    <h3 class="uk-h1 uk-text-muted uk-text-center" v-show="comments && !comments.length">{{ 'No comments found.' | trans }}</h3>

    <v-pagination :current.sync="config.page" :pages="pages" v-show="pages > 1 || config.page > 0" v-model="config.page"></v-pagination>

</div>
