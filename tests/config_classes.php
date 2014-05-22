<?php

return array(
    'Model\Article' => array(
        'fields' => array(
            'title' => 'string',
        ),
    ),
    // Archivable
    'Model\Archivable' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Tokenizable'),
            array('class' => 'Mongator\Behavior\Archivable'),
        ),
    ),
    'Model\ArchivableReference' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'referencesOne' => array(
            'article' => array('class' => 'Model\Article'),
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Archivable')
        ),
    ),
    'Model\ArchivableInsert' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class'   => 'Mongator\Behavior\Archivable',
                'options' => array('archive_on_insert' => true),
            )
        ),
    ),
    'Model\ArchivableUpdate' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class'   => 'Mongator\Behavior\Archivable',
                'options' => array('archive_on_update' => true),
            )
        ),
    ),
    'Model\ArchivableDelete' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class'   => 'Mongator\Behavior\Archivable',
                'options' => array('archive_on_delete' => true),
            )
        ),
    ),
    // Tokenizable
    'Model\Tokenizable' => array(
        'fields' => array(
            'field' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Tokenizable',),
        ),
    ),
    'Model\TokenizableField' => array(
        'fields' => array(
            'field' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Tokenizable', 'options' => array('field' => 'anotherField')),
        ),
    ),
    'Model\TokenizableLength' => array(
        'fields' => array(
            'field' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Tokenizable', 'options' => array('length' => 5)),
        ),
    ),
    // Ipable
    'Model\Ipable' => array(
        'fields' => array(
            'field' => 'string',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Ipable',
            ),
        ),
    ),
    // Sluggable
    'Model\Sluggable' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class'   => 'Mongator\Behavior\Sluggable',
                'options' => array(
                    'fromField' => 'title',
                ),
            )
        ),
    ),
    'Model\GlobalSluggable' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class'   => 'Mongator\Behavior\Sluggable',
                'options' => array(
                    'fromField' => 'title',
                    'unique' => 'global',
                    'slugClass' => 'Model\Slug',
                ),
            )
        ),
    ),
    'Model\AnotherGlobalSluggable' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class'   => 'Mongator\Behavior\Sluggable',
                'options' => array(
                    'fromField' => 'title',
                    'unique' => 'global',
                    'slugClass' => 'Model\Slug',
                ),
            )
        ),
    ),
    // AutoIncrementable
    'Model\AutoIncrementable' => array(
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\AutoIncrementable',
                'options' => array(
                    'counterName' => 'test',
                    'counterField' => 'test',
                )
            ),
        ),
    ),
    'Model\AutoIncrementableCustom' => array(
        'connection' => 'alt',
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\AutoIncrementable',
                'options' => array(
                    'counterClass' => 'Model\OtherCounter',
                    'counterName' => 'test2',
                    'counterField' => 'test',
                )
            ),
        ),
    ),
    // Sortable
    'Model\Sortable' => array(
        'fields' => array(
            'name' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Sortable'),
        ),
    ),
    'Model\SortableTop' => array(
        'fields' => array(
            'name' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Sortable', 'options' => array('new_position' => 'top')),
        ),
    ),
    'Model\SortableScope' => array(
        'fields' => array(
            'type' => 'string',
            'name' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Sortable', 'options' => array('scope' => array('type'))),
        ),
    ),
    'Model\SortableScopeReference' => array(
        'fields' => array(
            'name' => 'string',
        ),
        'referencesOne' => array(
            'sortable' => array('class' => 'Model\Sortable'),
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Sortable', 'options' => array('scope' => array('sortable'))),
        ),
    ),
    'Model\SortableSkip' => array(
        'fields' => array(
            'name' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Sortable'),
        ),
    ),
    'Model\SortableParent' => array(
        'inheritable' => array('type' => 'single'),
        'fields' => array(
            'name' => 'string',
        ),
        'behaviors' => array(
            array('class' => 'Mongator\Behavior\Sortable'),
        ),
    ),
    'Model\SortableChild' => array(
        'inheritance' => array('class' => 'Model\SortableParent', 'value' => 'child'),
    ),
    // Timestampable
    'Model\Timestampable' => array(
        'fields' => array(
            'field' => 'string'
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Timestampable',
            )
        ),
    ),
    // Hashable
    'Model\Hashable' => array(
        'fields' => array(
            'title' => 'string',
            'content' => 'string'
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Hashable'
            )
        ),
    ),
    'Model\HashableFields' => array(
        'fields' => array(
            'title' => 'string',
            'content' => 'string'
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Hashable',
                'options' => array(
                    'fromFields' => array(
                        'title'
                    ),
                ),
            )
        ),
    ),
    'Model\HashableReferences' => array(
        'fields' => array(
            'title' => 'string',
            'content' => 'string'
        ),
        'referencesOne' => array(
            'refOne' => array('class' => 'Model\Hashable'),
        ),
        'referencesMany' => array(
            'refMany' => array('class' => 'Model\Hashable'),
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Hashable',
            )
        ),
    ),
    'Model\HashableEmbedded' => array(
        'fields' => array(
            'title' => 'string',
            'content' => 'string'
        ),
        'embeddedsOne' => array(
            'embOne' => array('class' => 'Model\Comment'),
        ),
        'embeddedsMany' => array(
            'embMany' => array('class' => 'Model\Comment'),
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Hashable'
            )
        ),
    ),
    'Model\HashableConfigured' => array(
        'fields' => array(
            'title' => 'string',
            'content' => 'string'
        ),
        'referencesOne' => array(
            'refOne' => array('class' => 'Model\Hashable'),
        ),
        'referencesMany' => array(
            'refMany' => array('class' => 'Model\Hashable'),
        ),
        'embeddedsOne' => array(
            'embOne' => array('class' => 'Model\Comment'),
        ),
        'embeddedsMany' => array(
            'embMany' => array('class' => 'Model\Comment'),
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Hashable',
                'options' => array(
                    'fromFields' => array(
                        'title',
                        'refOne_reference_field',
                        'embMany'
                    ),
                ),
            )
        ),
    ),
    'Model\Comment' => array(
        'isEmbedded' => true,
        'fields' => array(
            'title' => 'string',
            'content' => 'string'
        )
    ),

    // Identifiable
    'Model\IdentifiableDocument' => array(
       'embeddedsOne' => array(
            'embOne' => array('class' => 'Model\Identifiable'),
            'embTwo' => array('class' => 'Model\IdentifiableCustom'),
        ),
    ),

    'Model\Identifiable' => array(
        'isEmbedded' => true,
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Identifiable',
                'options' => array(),
            )
        ),
    ),

    'Model\IdentifiableCustom' => array(
        'isEmbedded' => true,
        'fields' => array(
            'title' => 'string',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Identifiable',
                'options' => array(
                    'idGeneratorMethod' => array(
                        'Mongator\Tests\Behavior\IdentifiableTest', 'generateId'
                    )
                ),
            )
        ),
    ),

    // EmbeddedUniquable
    'Model\EmbeddedUniquable' => array(
        'embeddedsMany' => array(
            'foo' => array('class' => 'Model\Comment'),
        ),
        'behaviors' => array(
            array(
                'class'   => 'Mongator\Behavior\EmbeddedUniquable',
                'options' => array(
                    'fields' => array(array(
                        'embeddedsMany' => 'foo', 
                        'field' => 'title'
                    )),
                ),
            )
        ),
    ),

    // Stringifiable
    'Model\Stringifiable' => array(
        'fields' => array(
            'title' => 'string',
            'content'  => 'string',
            'note'     => 'string',
            'line'     => 'string',
            'text'     => 'string',
            'isActive' => 'boolean',
            'date'     => 'date',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Stringifiable',
                'options' => array(
                    'fromFields' => array(
                        'title',
                        'date' => 'Y-m-d\TH:i:sP'
                    )
                ),
            ),
            array(
                'class'   => 'Mongator\Behavior\Sluggable',
                'options' => array(
                    'fromToString' => true,
                ),
            )
        ),
    ),

    'Model\StringifiableWithFormat' => array(
        'fields' => array(
            'title' => 'string',
            'content'  => 'string',
            'note'     => 'string',
            'line'     => 'string',
            'text'     => 'string',
            'isActive' => 'boolean',
            'date'     => 'date',
        ),
        'behaviors' => array(
            array(
                'class' => 'Mongator\Behavior\Stringifiable',
                'options' => array(
                    'format' => '%s/%s/%s',
                    'fromFields' => array(
                        'title',
                        'note',
                        'text'
                    )
                ),
            )
        ),
    ),
);
