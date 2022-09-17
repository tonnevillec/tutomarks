import React from 'react';

const Tag = (props) => {
    return (
        <p className="post-tags">
            {props.list.map(tag =>
                <a href="#"
                   className="label label-default"
                   key={tag.id}
                >{tag.title}</a>
            )}
        </p>
    );
};

export default Tag;
