
import React from "react";
import { Link } from "@inertiajs/react";
import {
    Grid,
    Card,
    CardActionArea,
    CardContent,
    CardMedia,
    Typography,
    CardHeader ,
    Box,
} from "@mui/material";

interface MenuItem {
    url: string;
    title: string;
    description: string;
    imagePath: string;
}

interface MenuGridProps {
    items: MenuItem[];
}

const MenuGrid = () => {
    const items = [
          
        {
            url: 'profile',
            title: 'Profile',
            description: 'change your personal details here',
            imagePath: '/images/profile.png',
        },
        {
            url: 'register',
            title: 'Register another person',
            description: 'Register someone else to have access to this website',
            imagePath: '/images/register.jpg',
        },

            {
            url: 'category',
            title: 'Categories',
            description: 'a bit of a description about categories',
            imagePath: '',
        },
        {
            url: 'contact',
            title: 'Contacts',
            description: 'a bit of a description about contacts',
            imagePath: '',
        },
        {
            url: 'customer',
            title: 'Customers',
            description: 'a bit of a description about customers',
            imagePath: '',
        },

    ];
    return (
        <Grid container spacing={2}>
            {items.map((item, index) => (
                <Grid item xs={12} sm={6} md={4} lg={3} key={index}>
                    <Link href={item.url}>
                        <Card
                            sx={{
                                minWidth: 100,
                                minHeight: 100,
                                display: "flex",
                                flexDirection: "column",
                                height: "100%",
                                transition: "0.3s",
                                "&:hover": {
                                    transform: "scale(1.05)",
                                },
                                textDecoration: "none", // Remove default link styling
                            }}
                        >
                            <CardActionArea
                                sx={{
                                    flexGrow: 1,
                                    display: "flex",
                                    flexDirection: "column",
                                    alignItems: "stretch",
                                }}
                            >
                                {item.imagePath ? (
                                  <CardMedia
                                    component="img"
                                    height={140}
                                    image={item.imagePath || "/api/placeholder/400/320"}
                                    alt={item.title}
                                  />
                                ) : (
                                  <CardHeader
                                    title={item.title}
                                    titleTypographyProps={{ variant: "h4" }}
                                  />
                                )}
                                <CardContent
                                    sx={{
                                        flexGrow: 1,
                                        display: "flex",
                                        flexDirection: "column",
                                        justifyContent: "space-between",
                                    }}
                                >
                                    <Typography
                                        variant="body2"
                                        color="text.secondary"
                                    >
                                        {item.description || " "}
                                    </Typography>
                                </CardContent>
                            </CardActionArea>
                        </Card>
                    </Link>
                </Grid>
            ))}
        </Grid>
    );
};

export default MenuGrid;

