import {FunctionComponent} from "react";
import {MediaObject} from "../../types/MediaObject";
import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import {FunctionField} from "react-admin";
// @ts-ignore
import {ENTRYPOINT} from "../../config/entrypoint.ts";

interface Props {
    mediaObjects: MediaObject[];
}

// @ts-ignore
export const List: FunctionComponent<Props> = ({mediaObjects}) => (
    <ListGuesser rowClick="show" {...mediaObjects}>
        <FieldGuesser source="name"/>
        <FunctionField
            label="Image"
            render={record => {return <img style={{ width:"100%", maxWidth: "150px", maxHeight: "150px"}} src={ENTRYPOINT + "/image/" + record.filePath} alt={record.name} />;}}
        />;
    </ListGuesser>
);
