<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="./dist/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="./dist/bootstrap/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" type="text/css" href="./dist/plugins/contextmenu.css" />
    <link rel="stylesheet" type="text/css" href="./src/css/defaultstyle.css"/>
    <title>流程图</title>
    <meta name="keywords" content="" />
    <meta name="description" content=" " />
    <style type="text/css">
    </style>
</head>
<body>
    <div class="container">
        <div>
            <div id="workflow"></div>
        </div>

        <div id="output"></div>
        <input id="submit" type="button" class="btn" value='导出结果' onclick="Export()"/>
        <textarea id="result" rows="12" style="width:100%;"></textarea>
    </div>

    <div id="workAreaMenu" style="display:none">
        <ul class="contextMenu">
            <li id="workflowproperty"><span class="_label">流程属性</span></li>
            <li class="has-sub"><span class="_label">连线样式</span>
                <ul class="submenu">
                    <li id="bezier"><span class="_label">贝塞尔曲线</span> <span class="glyphicon glyphicon-ok"></span></li>
                    <li id="straight"><span class="_label">直线</span></li>
                    <li id="flowchart"><span class="_label">折线</span></li>
                    <li id="statemachine"><span class="_label">弧线</span></li>
                </ul>
            </li>
        </ul>
    </div>

    <div id="nodeMenu" class="contextMenu" style="display:none">
        <ul class="contextMenu">
            <li id="workflowinfo"><span class="_label">步骤概览</span></li>
            <li class="has-sub"><span class="_label">节点类型</span>
                <ul class="submenu">
                    <li id="nodetype_start"><span class="_label">起始节点</span></li>
                    <li id="nodetype_end"><span class="_label">结束节点</span></li>
                    <li id="nodetype_node"><span class="_label">普通节点</span></li>
                </ul>
            </li>
            <li class="br"></li>
            <li id="property"><span class="_label">基本属性</span></li>
            <li id="description"><span class="_label">步骤要求</span></li>
            <li id="turnright"><span class="_label">经办权限</span></li>
            <li id="writeablefield"><span class="_label">可写字段</span></li>
            <li id="turncondition"><span class="_label">转交条件</span></li>
            <li class="br"></li>
            <li id="delnode"><span class="_label">删除步骤</span></li>
        </ul>
    </div>

    <script src="./dist/jquery2.js"></script>
    <script src="./data.js"></script>
    <script src="./dist/jsPlumb-2.0.6.js"></script>
    <script src="./dist/plugins/jquery.contextmenu.r2.js"></script>
    <script src="./dist/plugins/layer.js"></script>
    <script src="./src/jhworkflow.js"></script>
    <script>
        
        var workAreaMenuJson = {
            id: 'workAreaMenu',
            bindings:{
                workflowproperty: function() {
                    layer.msg('流程图属性');
                },
                bezier: function() {
                    workflow.Line.changeType('Bezier');
                },
                straight: function() {
                    workflow.Line.changeType('Straight');
                },
                flowchart: function() {
                    workflow.Line.changeType('Flowchart');
                },
                statemachine: function() {
                    workflow.Line.changeType('StateMachine');
                }
            }
        };
        var nodeMenuJson = {
            id: 'nodeMenu',
            bindings:{
                nodetype_start:function(t){
                    workflow.Node.changeType(t.id, 'start');
                },
                nodetype_end:function(t){
                    workflow.Node.changeType(t.id, 'end');
                },
                nodetype_node:function(t){
                    workflow.Node.changeType(t.id, 'node');
                }
            }
        };

        var property = {
            width: 1200,
            height: 600,
            haveHead: true,
            headLabel: '新建流程',
            headBtns: ["save", "undo", "redo"],
            haveTool: true,
            haveGroup: true,
            rollback: true,
            workAreaMenu: workAreaMenuJson,
            nodeMenu: nodeMenuJson
        };

        function Export() {
            document.getElementById("result").value = JSON.stringify(workflow.exportData());
        }

        var workflow;
        workflow = JHWorkFlow.createInstance('#workflow', property);
        workflow.ready(function() {
            workflow.onNodeShowMenu = function(e, menu) {
                menu.find('#nodetype_')
                return menu;
            }

            workflow.onBtnSaveClick = function() {
                $.post('action.php', {data:workflow.exportData()}, function(data) {
                    if(data == 'ok') {
                        alert('保存成功！');
                        workflow.dataChanged(true);
                    }
                });
            }

            workflow.loadData(jsondata);

        });
    </script>
</body>
</html>

