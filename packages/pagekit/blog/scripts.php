<?php

return [

    'install' => function ($app) {

        $util = $app['db']->getUtility();

        if ($util->tableExists('@blog_post') === false) {
            $util->createTable('@blog_post', function ($table) {
                $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
                $table->addColumn('user_id', 'integer', ['unsigned' => true, 'length' => 10, 'default' => 0]);
                $table->addColumn('slug', 'string', ['length' => 255]);
                $table->addColumn('title', 'string', ['length' => 255]);
                $table->addColumn('status', 'smallint');
                $table->addColumn('date', 'datetime', ['notnull' => false]);
                $table->addColumn('modified', 'datetime');
                $table->addColumn('content', 'text');
                $table->addColumn('excerpt', 'text');
                $table->addColumn('comment_status', 'boolean', ['default' => false]);
                $table->addColumn('comment_count', 'integer', ['default' => 0]);
                $table->addColumn('data', 'json_array', ['notnull' => false]);
                $table->addColumn('roles', 'simple_array', ['notnull' => false]);
                $table->setPrimaryKey(['id']);
                $table->addUniqueIndex(['slug'], '@BLOG_POST_SLUG');
                $table->addIndex(['title'], '@BLOG_POST_TITLE');
                $table->addIndex(['user_id'], '@BLOG_POST_USER_ID');
                $table->addIndex(['date'], '@BLOG_POST_DATE');
            });
        }

        if ($util->tableExists('@blog_comment') === false) {
            $util->createTable('@blog_comment', function ($table) {
                $table->addColumn('id', 'integer', ['unsigned' => true, 'length' => 10, 'autoincrement' => true]);
                $table->addColumn('parent_id', 'integer', ['unsigned' => true, 'length' => 10]);
                $table->addColumn('post_id', 'integer', ['unsigned' => true, 'length' => 10]);
                $table->addColumn('user_id', 'string', ['length' => 255]);
                $table->addColumn('author', 'string', ['length' => 255]);
                $table->addColumn('email', 'string', ['length' => 255]);
                $table->addColumn('url', 'string', ['length' => 255, 'notnull' => false]);
                $table->addColumn('ip', 'string', ['length' => 255]);
                $table->addColumn('created', 'datetime');
                $table->addColumn('content', 'text');
                $table->addColumn('status', 'smallint');
                $table->setPrimaryKey(['id']);
                $table->addIndex(['author'], '@BLOG_COMMENT_AUTHOR');
                $table->addIndex(['created'], '@BLOG_COMMENT_CREATED');
                $table->addIndex(['status'], '@BLOG_COMMENT_STATUS');
                $table->addIndex(['post_id'], '@BLOG_COMMENT_POST_ID');
                $table->addIndex(['post_id', 'status'], '@BLOG_COMMENT_POST_ID_STATUS');
            });
        }

    },

    'uninstall' => function ($app) {

        $util = $app['db']->getUtility();

        if ($util->tableExists('@blog_post')) {
            $util->dropTable('@blog_post');
        }

        if ($util->tableExists('@blog_comment')) {
            $util->dropTable('@blog_comment');
        }
    },

    'updates' => [

        '0.11.2' => function ($app) {

            $db = $app['db'];
            $util = $db->getUtility();

            foreach (['@blog_post', '@blog_comment'] as $name) {
                $table = $util->getTable($name);

                foreach ($table->getIndexes() as $name => $index) {
                    if ($name !== 'primary') {
                        $table->renameIndex($index->getName(), $app['db']->getPrefix() . $index->getName());
                    }
                }

                if ($app['db']->getDatabasePlatform()->getName() === 'sqlite') {
                    foreach ($table->getColumns() as $column) {
                        if (in_array($column->getType()->getName(), ['string', 'text'])) {
                            $column->setOptions(['customSchemaOptions' => ['collation' => 'NOCASE']]);
                        }
                    }
                }
            }

            $util->migrate();
        }

    ]

];