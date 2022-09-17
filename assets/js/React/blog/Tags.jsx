import React from 'react';

const Tags = ({list}) => {
    return <div className={"video-tags"}>
        {list.map(tag =>
            <a href={"/search/?tags[]=" + tag.id}
               title={tag.title}
               data-toggle="tooltip"
               data-placement="top"
               key={tag.id}
               className={"me-2"}
            ><span className="badge bg-primary rounded">{tag.title}</span>
            </a>
        )}
    </div>;
};

export default Tags;