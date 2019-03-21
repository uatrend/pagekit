<?php $view->script('update', 'installer:app/bundle/update.js', ['vue', 'marked']) ?>

<div id="update" v-cloak>

    <p class="uk-alert uk-alert-warning" v-for="error in errorsPagekit">{{ error }}</p>

    <div v-show="update && view == 'index'">

        <div v-show="hasUpdate">

            <template v-if="!update.msg">
                <h2>{{ 'There is an update available.' | trans }}</h2>
                <p>{{ 'Update to Pagekit %version% automatically or download the package and install it manually! Read the changelog below to see what\'s new.' | trans(update) }}</p>
            </template>

            <p v-html="update.msg" v-else></p>
        </div>

        <div v-show="!hasUpdate">
            <h2>{{ 'You have the latest version of Pagekit.' | trans }}</h2>
            <p>{{ 'You have the latest version of Pagekit. You do not need to update. However, if you want to re-install version %version%, you can download the package and re-install manually.' | trans(update) }}</p>
        </div>

        <p>
            <a class="uk-button uk-button-primary" @click.prevent="install" v-show="hasUpdate">
                <span>{{ 'Update' | trans }}</span>
            </a>
            <a class="uk-button uk-button-default" :href="update.url">{{ 'Download %version%' | trans(update) }}</a>
        </p>

        <hr class="uk-margin-large">

        <h2 v-show="hasUpdate">{{ 'Changelog' | trans }}</h2>
        <div v-for="release in releases" v-if="showChangelog(release.version)">

            <h2>{{ release.version }} <small class="uk-text-muted">/ <time :datetime="release.published_at" :title="release.published_at | date">{{ release.published_at | relativeDate({max:2592000}) }}</time></small></h2>
            <ul class="uk-list uk-list-large" v-html="changelog(release.changelog)"></ul>

        </div>

    </div>

    <div v-if="view == 'installation'">

        <p>{{ message | trans }}</p>

        <div class="uk-progress uk-progress-striped" :class="{
            'uk-progress-danger':  errorsPagekit.length,
            'uk-progress-success': progress == 100,
            'uk-active':           progress != 100 && !errorsPagekit.length
        }">
            <div class="uk-progress-bar" :style="{width: progress + '%'}">{{ progress }}%</div>
        </div>

        <pre v-html="output" v-show="output"></pre>

        <a :class="'uk-button uk-button-default uk-button-' + status" :href="$url.route('admin')" v-show="finished">{{ 'Ok' | trans }}</a>

    </div>

</div>
