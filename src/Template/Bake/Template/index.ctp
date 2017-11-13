<%
/**
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link          http://cakephp.org CakePHP(tm) Project
* @since         0.1.0
* @license       http://www.opensource.org/licenses/mit-license.php MIT License
*/
%>
<?php
/**
 * @var \<%= $namespace %>\View\AppView $this
 * @var \<%= $entityClass %>[]|\Cake\Collection\CollectionInterface $<%= $pluralVar %>
 */
?>
<%
use Cake\Utility\Inflector;

$fields = collection($fields)
->filter(function($field) use ($schema) {
return !in_array($schema->columnType($field), ['binary', 'text']);
});

if (isset($modelObject) && $modelObject->behaviors()->has('Tree')) {
$fields = $fields->reject(function ($field) {
return $field === 'lft' || $field === 'rght';
});
}

if (!empty($indexColumns)) {
$fields = $fields->take($indexColumns);
}

%>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= __('<%= $pluralHumanName %>') ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $this->Url->build(array("action" => "do-operation")) ?>" method="post">
                    <!--Table Wrapper Start-->
                    <div class="table-responsive ls-table">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <% foreach ($fields as $field):
                                if($field=='id'){
                                %>
                                <th><input type="checkbox" id="checkall" /></th>
                                <%
                                }else{ %>
                                <th scope="col"><?= $this->Paginator->sort('<%= $field %>') ?></th>
                                <% } %>
                                <% endforeach; %>
                                <th scope="col" class="actions"><?= __('Actions') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($<%= $pluralVar %> as $<%= $singularVar %>): ?>
                            <tr>
                                <%        foreach ($fields as $field) {
                                if($field =='id'){%>
                                <td> <input  type="checkbox" name="chk[]" value="<?php echo $<%= $singularVar.'->id' %> ?>" /> </td>
                                <%
                                }else{

                                $isKey = false;
                                if (!empty($associations['BelongsTo'])) {
                                foreach ($associations['BelongsTo'] as $alias => $details) {
                                if ($field === $details['foreignKey']) {
                                $isKey = true;
                                %>
                                <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
                                <%
                                break;
                                }
                                }
                                }
                                if ($isKey !== true) {
                                if (!in_array($schema->columnType($field), ['integer', 'float', 'decimal', 'biginteger', 'smallinteger', 'tinyinteger'])) {
                                %>
                                <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
                                <%
                                } else {
                                %>
                                <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
                                <%
                                }
                                }
                                }
                                }

                                $pk = '$' . $singularVar . '->' . $primaryKey[0];
                                %>
                                <td class="actions">

                                    <?= $this->Html->link('<i class="fa fa-pencil"></i> ' .__('Edit'), ['action' => 'edit', <%= $pk %>], ['class' => 'btn btn-primary', 'escape' => false]) ?>
                    <?= $this->Form->postLink('<i class="fa fa-trash-o"></i> ' .__('Delete'), ['action' => 'delete', <%= $pk %>], ['class' => 'btn btn-danger', 'escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>)]) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" id="acts" name="operation">
                                    <option value=""><?php echo __("Choose Operation") ?></option>
                                    <option value="delete"><?php echo __("Delete") ?></option>
                                </select>
                            </div>
                            <div class="col-md-9 text-right">
                                <ul class="pagination ls-pagination">
                                    <?php
                                    if ($this->Paginator->hasPrev()) {
                                    echo $this->Paginator->prev('< ' . __('previous'));
                                    }
                                    echo $this->Paginator->numbers();
                                    if ($this->Paginator->hasNext()) {
                                    echo $this->Paginator->next(__('next') . ' >');
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
