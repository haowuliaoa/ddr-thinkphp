<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class InitTable extends AbstractMigration
{
    public function change()
    {
        if ($this->hasTable('user') == false) {
            $table = $this->table('user', ['comment' => '用户基本信息表']);
            $table->addColumn('user_name', 'string', ['default' => '', 'limit' => '50', 'comment' => '真实姓名']);
            $table->addColumn('real_name', 'string', ['default' => '', 'limit' => '20', 'comment' => '真实姓名']);
            $table->addColumn('sex', 'integer', ['default' => 1, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '1男2女']);
            $table->addColumn('role_id', 'integer', ['default' => 0, 'comment' => '角色id，对应role表']);
            $table->addColumn('pwd', 'string', ['default' => '', 'limit' => '50', 'comment' => '密码']);
            $table->addColumn('wechat_openid', 'string', ['default' => '', 'limit' => '20', 'comment' => '职务，比如：局长，副局长']);
            $table->addColumn('wechat_unionid', 'string', ['default' => '', 'limit' => '100', 'comment' => '加密的密码']);
            $table->addColumn('is_admin', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '1是超级管理员 2是一般管理员']);
            $table->addIndex('role_id')->save();
        }

        if (!$this->hasTable('role')) {
            $table = $this->table("role", ['comment' => "角色基本表"]);
            $table->addColumn('role_name', 'string', ['default' => '', 'limit' => '50', 'comment' => '角色名称']);
            $table->save();
        }
        if (!$this->hasTable('document')) {
            $table = $this->table("document", ['comment' => "文档表"]);
            $table->addColumn('file_name', 'string', ['default' => '', 'limit' => '100', 'comment' => '文档名称']);
            $table->addColumn('file_describe', 'string', ['default' => '', 'limit' => '255', 'comment' => '文档描述']);
            $table->addColumn('file_url', 'string', ['default' => '', 'limit' => '150', 'comment' => '文档地址，指向OSS']);
            $table->addColumn('file_type', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '文档类型 1=>文本 2=>音频 3=>视频']);
            $table->addColumn('file_from_department', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '文档来源的部门，1=>点灯人 2=>信息技术部 3=>品牌中心 4=>研究中心 5=>公益部 6=>财务部 7=>行政人事部 8=>图书研发部 9=>小步 10=>学堂部']);
            $table->addIndex('file_type')->addIndex('file_from_department')->save();
        }

        if (!$this->hasTable('role_permission')) {
            $table = $this->table("role_permission", ['comment' => "角色的权限表（能看哪些文档，是否可以下载）"]);
            $table->addColumn('role_id', 'integer', ['comment' => '角色id', 'default' => 0]);
            $table->addColumn('doc_id', 'integer', ['comment' => '对应document表主键', 'default' => 0]);
            $table->addColumn('if_can_download', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '是否可以下载 1=>可下载']);
            $table->addColumn('if_can_view', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '是否可以查看 1=>可查看']);
            $table->addIndex('role_id')->addIndex('doc_id')->save();
        }

        if (!$this->hasTable('user_permission')) {
            $table = $this->table("user_permission", ['comment' => "用户权限表，用户可能有角色，则取并集；无角色，则以此表权限为准"]);
            $table->addColumn('user_id', 'integer', ['comment' => '用户id，对应user表', 'default' => 0]);
            $table->addColumn('doc_id', 'integer', ['comment' => '对应document表，是表示该用户的权限（结合其role角色，取并集）', 'default' => 0]);
            $table->addColumn('if_can_download', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '是否可以下载 1=>可下载']);
            $table->addColumn('if_can_view', 'integer', ['default' => 0, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '是否可以查看 1=>可查看']);
            $table->addIndex('user_id')->addIndex('doc_id')->save();
        }
    }
}
