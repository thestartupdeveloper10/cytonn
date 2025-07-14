@component('mail::message')
# New Task Assigned

Hello {{ $task->assignedTo->name }},

You have been assigned a new task:

**Title:** {{ $task->title }}

@if($task->description)
**Description:** {{ $task->description }}
@endif

@if($task->deadline)
**Deadline:** {{ $task->deadline }}
@endif

Assigned by: {{ $task->createdBy->name }}

Thanks,cytonn
@endcomponent
